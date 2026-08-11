<?php
/**
 * Variation generation service.
 *
 * @package BulkVariationCodeGenerator
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

	class BVCG_Generator {

	/**
	 * Default batch size.
	 */
	const DEFAULT_BATCH_SIZE = 32;

	/**
	 * Creates a generation job from posted data.
	 *
	 * @param array $payload Raw payload.
	 * @return array|WP_Error
	 */
	public function create_job( array $payload ) {
		$normalized = $this->normalize_payload( $payload );

		if ( is_wp_error( $normalized ) ) {
			return $normalized;
		}

		$product = wc_get_product( $normalized['product_id'] );

		if ( ! $product || ! $product->is_type( 'variable' ) ) {
			return new WP_Error( 'bvcg_invalid_product', __( 'Only variable products can be processed.', 'bulk-variation-code-generator' ) );
		}

		$term_ids = $this->prime_terms_for_items( $normalized['attribute']['taxonomy'], $normalized['items'] );

		if ( is_wp_error( $term_ids ) ) {
			return $term_ids;
		}

		$this->sync_product_attribute( $product, $normalized['attribute']['taxonomy'], $term_ids );

		$image_payload = $this->prepare_uploaded_images( $normalized['items'] );

		if ( is_wp_error( $image_payload ) ) {
			return $image_payload;
		}

		$seo_template = $this->normalize_seo_template( $payload );

		$token = wp_generate_uuid4();
		$key   = $this->get_job_key( $token );

		set_transient(
			$key,
			array(
				'created_at' => time(),
				'product_id'  => $normalized['product_id'],
				'attribute'   => $normalized['attribute'],
				'items'       => $normalized['items'],
				'total'       => count( $normalized['items'] ),
				'processed'       => 0,
				'created'         => 0,
				'skipped'         => 0,
				'images_assigned' => 0,
				'images_skipped'  => 0,
				'images_missing'  => 0,
				'errors'          => array(),
				'image_map'       => $image_payload['map'],
				'image_stats'     => $image_payload['stats'],
				'seo_template'    => $seo_template,
			),
			12 * HOUR_IN_SECONDS
		);

		return array(
			'token'   => $token,
			'preview' => $this->build_preview( $normalized['items'] ),
			'total'   => count( $normalized['items'] ),
			'images'  => $image_payload['stats'],
		);
	}

	/**
	 * Processes a batch of an existing job.
	 *
	 * @param string $token Job token.
	 * @param int    $batch_size Batch size.
	 * @return array|WP_Error
	 */
	public function process_job( $token, $batch_size = self::DEFAULT_BATCH_SIZE ) {
		$token = sanitize_text_field( (string) $token );
		$key   = $this->get_job_key( $token );
		$job   = get_transient( $key );

		if ( empty( $job ) || ! is_array( $job ) ) {
			return new WP_Error( 'bvcg_missing_job', __( 'The generation job could not be found or has expired.', 'bulk-variation-code-generator' ) );
		}

		$batch_size = max( 1, absint( $batch_size ) );
		$items      = isset( $job['items'] ) && is_array( $job['items'] ) ? $job['items'] : array();
		$total      = isset( $job['total'] ) ? absint( $job['total'] ) : count( $items );
		$processed   = isset( $job['processed'] ) ? absint( $job['processed'] ) : 0;

		if ( empty( $items ) ) {
			delete_transient( $key );

			return array(
				'total'     => 0,
				'processed' => 0,
				'created'   => 0,
				'skipped'   => 0,
				'errors'    => array(),
				'done'      => true,
			);
		}

		$product = wc_get_product( absint( $job['product_id'] ) );

		if ( ! $product || ! $product->is_type( 'variable' ) ) {
			delete_transient( $key );

			return new WP_Error( 'bvcg_invalid_product', __( 'Only variable products can be processed.', 'bulk-variation-code-generator' ) );
		}

		$existing_variations = $this->get_existing_variations( $product, $job['attribute']['taxonomy'] );
		$batch_items         = array_slice( $items, $processed, $batch_size );
		$created             = isset( $job['created'] ) ? absint( $job['created'] ) : 0;
		$skipped             = isset( $job['skipped'] ) ? absint( $job['skipped'] ) : 0;
		$images_assigned     = isset( $job['images_assigned'] ) ? absint( $job['images_assigned'] ) : 0;
		$images_skipped      = isset( $job['images_skipped'] ) ? absint( $job['images_skipped'] ) : 0;
		$images_missing      = isset( $job['images_missing'] ) ? absint( $job['images_missing'] ) : 0;
		$errors              = isset( $job['errors'] ) && is_array( $job['errors'] ) ? $job['errors'] : array();
		$image_map           = isset( $job['image_map'] ) && is_array( $job['image_map'] ) ? $job['image_map'] : array();
		$seo_template        = isset( $job['seo_template'] ) && is_array( $job['seo_template'] ) ? $job['seo_template'] : array();

		foreach ( $batch_items as $item ) {
			$code = isset( $item['code'] ) ? (string) $item['code'] : '';
			$attachment_id = $this->get_image_attachment_id_for_code( $code, $image_map );
			$variation_id  = isset( $existing_variations[ $code ] ) ? absint( $existing_variations[ $code ] ) : 0;

			if ( '' === $code ) {
				$skipped++;
				continue;
			}

			if ( $variation_id > 0 ) {
				$skipped++;
				if ( $attachment_id > 0 ) {
					$image_result = $this->assign_variation_image( $variation_id, $attachment_id, false, $seo_template );

					if ( is_wp_error( $image_result ) ) {
						$errors[] = $image_result->get_error_message();
					} elseif ( ! empty( $image_result['assigned'] ) ) {
						$images_assigned++;
					} else {
						$images_skipped++;
					}
				} elseif ( ! empty( $image_map ) ) {
					$images_missing++;
				}
				continue;
			}

			$term = get_term_by( 'name', $code, $job['attribute']['taxonomy'] );

			if ( ! $term || is_wp_error( $term ) ) {
				$errors[] = sprintf( __( 'Term not found for %s.', 'bulk-variation-code-generator' ), $code );
				$skipped++;
				continue;
			}

			$variation_result = $this->create_variation( $product, $job['attribute']['taxonomy'], $term, $item );

			if ( is_wp_error( $variation_result ) ) {
				$errors[] = $variation_result->get_error_message();
				$skipped++;
				continue;
			}

			$variation_id = (int) $variation_result;

			if ( $attachment_id > 0 ) {
				$image_result = $this->assign_variation_image( $variation_id, $attachment_id, false, $seo_template );

				if ( is_wp_error( $image_result ) ) {
					$errors[] = $image_result->get_error_message();
				} elseif ( ! empty( $image_result['assigned'] ) ) {
					$images_assigned++;
				} else {
					$images_skipped++;
				}
			} elseif ( ! empty( $image_map ) ) {
				$images_missing++;
			}
			$created++;
		}

		$processed += count( $batch_items );

		if ( $processed >= $total ) {
			delete_transient( $key );

			return array(
				'total'     => $total,
				'processed' => $total,
				'created'   => $created,
				'skipped'   => $skipped,
				'images_assigned' => $images_assigned,
				'images_skipped'   => $images_skipped,
				'images_missing'   => $images_missing,
				'errors'    => $errors,
				'done'      => true,
			);
		}

		$job['processed'] = $processed;
		$job['created']   = $created;
		$job['skipped']   = $skipped;
		$job['images_assigned'] = $images_assigned;
		$job['images_skipped']   = $images_skipped;
		$job['images_missing']   = $images_missing;
		$job['errors']    = $errors;

		set_transient( $key, $job, 12 * HOUR_IN_SECONDS );

		return array(
			'total'     => $total,
			'processed' => $processed,
			'created'   => $created,
			'skipped'   => $skipped,
			'images_assigned' => $images_assigned,
			'images_skipped'   => $images_skipped,
			'images_missing'   => $images_missing,
			'errors'    => $errors,
			'done'      => false,
		);
	}

	/**
	 * Normalizes request data into a validated generation payload.
	 *
	 * @param array $payload Payload.
	 * @return array|WP_Error
	 */
	public function normalize_payload( array $payload ) {
		$product_id = isset( $payload['product_id'] ) ? absint( $payload['product_id'] ) : 0;
		$product    = wc_get_product( $product_id );

		if ( ! $product ) {
			return new WP_Error( 'bvcg_invalid_product', __( 'Invalid product.', 'bulk-variation-code-generator' ) );
		}

		if ( ! $product->is_type( 'variable' ) ) {
			return new WP_Error( 'bvcg_not_variable', __( 'Only variable products are supported.', 'bulk-variation-code-generator' ) );
		}

		$attribute_taxonomy = isset( $payload['attribute'] ) ? sanitize_text_field( wp_unslash( $payload['attribute'] ) ) : '';

		if ( '' === $attribute_taxonomy || ! taxonomy_exists( $attribute_taxonomy ) ) {
			return new WP_Error( 'bvcg_invalid_attribute', __( 'The selected attribute does not exist.', 'bulk-variation-code-generator' ) );
		}

		$ranges = $this->extract_ranges( $payload );

		if ( is_wp_error( $ranges ) ) {
			return $ranges;
		}

		$items = $this->build_items_from_ranges( $ranges );

		if ( empty( $items ) ) {
			return new WP_Error( 'bvcg_no_items', __( 'No valid codes were generated.', 'bulk-variation-code-generator' ) );
		}

		return array(
			'product_id' => $product_id,
			'attribute'  => array(
				'taxonomy' => $attribute_taxonomy,
			),
			'items'      => $items,
		);
	}

	/**
	 * Builds preview data.
	 *
	 * @param array $items Items.
	 * @return array
	 */
	public function build_preview( array $items ) {
		$codes = wp_list_pluck( $items, 'code' );
		$count = count( $codes );

		return array(
			'first' => array_slice( $codes, 0, 3 ),
			'last'  => array_slice( $codes, max( 0, $count - 3 ) ),
			'total' => $count,
		);
	}

	/**
	 * Gets job cache key.
	 *
	 * @param string $token Token.
	 * @return string
	 */
	private function get_job_key( $token ) {
		return 'bvcg_job_' . sanitize_key( $token );
	}

	/**
	 * Extracts range definitions from the payload.
	 *
	 * @param array $payload Payload.
	 * @return array|WP_Error
	 */
	private function extract_ranges( array $payload ) {
		$ranges = array();

		if ( ! empty( $payload['csv_text'] ) ) {
			$ranges = $this->parse_csv_ranges( (string) wp_unslash( $payload['csv_text'] ) );
		} elseif ( ! empty( $payload['ranges'] ) && is_array( $payload['ranges'] ) ) {
			$ranges = $payload['ranges'];
		} else {
			$ranges[] = array(
				'prefix'       => isset( $payload['prefix'] ) ? sanitize_text_field( wp_unslash( $payload['prefix'] ) ) : '',
				'start'        => isset( $payload['start'] ) ? absint( $payload['start'] ) : 0,
				'end'          => isset( $payload['end'] ) ? absint( $payload['end'] ) : 0,
				'digits'       => isset( $payload['digits'] ) ? absint( $payload['digits'] ) : 3,
				'price'        => isset( $payload['price'] ) ? sanitize_text_field( wp_unslash( $payload['price'] ) ) : '',
				'sku_prefix'   => isset( $payload['sku_prefix'] ) ? sanitize_text_field( wp_unslash( $payload['sku_prefix'] ) ) : '',
				'stock_qty'    => isset( $payload['stock_qty'] ) ? absint( $payload['stock_qty'] ) : 100,
			);
		}

		if ( empty( $ranges ) ) {
			return new WP_Error( 'bvcg_empty_ranges', __( 'No ranges were found in the request.', 'bulk-variation-code-generator' ) );
		}

		$normalized = array();
		$fallback   = array(
			'prefix'     => isset( $payload['prefix'] ) ? sanitize_text_field( wp_unslash( $payload['prefix'] ) ) : '',
			'digits'     => isset( $payload['digits'] ) ? absint( $payload['digits'] ) : 3,
			'price'      => isset( $payload['price'] ) ? sanitize_text_field( wp_unslash( $payload['price'] ) ) : '',
			'sku_prefix' => isset( $payload['sku_prefix'] ) ? sanitize_text_field( wp_unslash( $payload['sku_prefix'] ) ) : '',
			'stock_qty'  => isset( $payload['stock_qty'] ) ? absint( $payload['stock_qty'] ) : 100,
		);

		foreach ( $ranges as $range ) {
			$prefix = isset( $range['prefix'] ) && '' !== $range['prefix'] ? sanitize_text_field( (string) $range['prefix'] ) : $fallback['prefix'];
			$start   = isset( $range['start'] ) ? absint( $range['start'] ) : 0;
			$end     = isset( $range['end'] ) ? absint( $range['end'] ) : 0;
			$digits  = isset( $range['digits'] ) ? absint( $range['digits'] ) : $fallback['digits'];
			$price   = isset( $range['price'] ) && '' !== $range['price'] ? sanitize_text_field( (string) $range['price'] ) : $fallback['price'];
			$sku     = isset( $range['sku_prefix'] ) && '' !== $range['sku_prefix'] ? sanitize_text_field( (string) $range['sku_prefix'] ) : $fallback['sku_prefix'];
			$stock   = isset( $range['stock_qty'] ) && '' !== $range['stock_qty'] ? absint( $range['stock_qty'] ) : $fallback['stock_qty'];

			if ( '' === $prefix ) {
				return new WP_Error( 'bvcg_empty_prefix', __( 'Prefix cannot be empty.', 'bulk-variation-code-generator' ) );
			}

			if ( $start < 1 || $end < 1 ) {
				return new WP_Error( 'bvcg_invalid_range', __( 'Start and end must be positive numbers.', 'bulk-variation-code-generator' ) );
			}

			if ( $start > $end ) {
				return new WP_Error( 'bvcg_invalid_range_order', __( 'Start must be less than or equal to end.', 'bulk-variation-code-generator' ) );
			}

			if ( $digits < 1 ) {
				return new WP_Error( 'bvcg_invalid_digits', __( 'Digits must be a positive number.', 'bulk-variation-code-generator' ) );
			}

			$normalized[] = array(
				'prefix'     => $prefix,
				'start'      => $start,
				'end'        => $end,
				'digits'     => $digits,
				'price'      => $price,
				'sku_prefix' => $sku,
				'stock_qty'  => $stock,
			);
		}

		return $normalized;
	}

	/**
	 * Parses CSV content into ranges.
	 *
	 * Supported headers: Prefix, Start, End, Price, Digits, SKU Prefix, Stock Quantity, Attribute.
	 *
	 * @param string $csv_text CSV text.
	 * @return array|WP_Error
	 */
	private function parse_csv_ranges( $csv_text ) {
		$csv_text = trim( (string) $csv_text );

		if ( '' === $csv_text ) {
			return new WP_Error( 'bvcg_empty_csv', __( 'CSV content is empty.', 'bulk-variation-code-generator' ) );
		}

		$handle = fopen( 'php://temp', 'r+' );

		if ( ! $handle ) {
			return new WP_Error( 'bvcg_csv_open_failed', __( 'Unable to read CSV content.', 'bulk-variation-code-generator' ) );
		}

		fwrite( $handle, $csv_text );
		rewind( $handle );

		$header = fgetcsv( $handle );

		if ( ! is_array( $header ) ) {
			fclose( $handle );

			return new WP_Error( 'bvcg_csv_header_missing', __( 'CSV header row is missing.', 'bulk-variation-code-generator' ) );
		}

		$header_map = array();

		foreach ( $header as $index => $column ) {
			$header_map[ strtolower( trim( (string) $column ) ) ] = (int) $index;
		}

		$ranges = array();

		while ( false !== ( $row = fgetcsv( $handle ) ) ) {
			if ( empty( array_filter( $row, 'strlen' ) ) ) {
				continue;
			}

			$ranges[] = array(
				'prefix'     => $this->csv_column_value( $row, $header_map, array( 'prefix' ), '' ),
				'start'      => $this->csv_column_value( $row, $header_map, array( 'start' ), 0 ),
				'end'        => $this->csv_column_value( $row, $header_map, array( 'end' ), 0 ),
				'digits'     => $this->csv_column_value( $row, $header_map, array( 'digits' ), 3 ),
				'price'      => $this->csv_column_value( $row, $header_map, array( 'price' ), '' ),
				'sku_prefix' => $this->csv_column_value( $row, $header_map, array( 'sku prefix', 'sku_prefix', 'sku-prefix' ), '' ),
				'stock_qty'   => $this->csv_column_value( $row, $header_map, array( 'stock quantity', 'stock_quantity', 'stock-quantity' ), 100 ),
			);
		}

		fclose( $handle );

		return $ranges;
	}

	/**
	 * Gets a CSV column value from aliases.
	 *
	 * @param array $row Row values.
	 * @param array $header_map Header map.
	 * @param array $aliases Aliases.
	 * @param mixed $default Default value.
	 * @return mixed
	 */
	private function csv_column_value( array $row, array $header_map, array $aliases, $default ) {
		foreach ( $aliases as $alias ) {
			$key = strtolower( trim( (string) $alias ) );

			if ( isset( $header_map[ $key ] ) && array_key_exists( $header_map[ $key ], $row ) ) {
				$value = trim( (string) $row[ $header_map[ $key ] ] );

				if ( '' !== $value ) {
					return is_numeric( $default ) ? absint( $value ) : sanitize_text_field( $value );
				}
			}
		}

		return $default;
	}

	/**
	 * Expands range definitions into individual items.
	 *
	 * @param array $ranges Ranges.
	 * @return array
	 */
	private function build_items_from_ranges( array $ranges ) {
		$items  = array();
		$seen   = array();

		foreach ( $ranges as $range ) {
			$start = absint( $range['start'] );
			$end   = absint( $range['end'] );
			$digits = absint( $range['digits'] );

			for ( $number = $start; $number <= $end; $number++ ) {
				$code = $range['prefix'] . sprintf( '%0' . $digits . 'd', $number );

				if ( isset( $seen[ $code ] ) ) {
					continue;
				}

				$seen[ $code ] = true;

				$items[] = array(
					'code'         => $code,
					'price'        => $range['price'],
					'sku_prefix'   => $range['sku_prefix'],
					'stock_qty'    => $range['stock_qty'],
				);
			}
		}

		return $items;
	}

	/**
	 * Returns existing variation codes for a product and taxonomy.
	 *
	 * @param WC_Product $product Product.
	 * @param string     $taxonomy Taxonomy.
	 * @return array
	 */
	private function get_existing_codes( WC_Product $product, $taxonomy ) {
		$existing = array();
		$children  = $product->get_children();

		foreach ( $children as $child_id ) {
			$variation = wc_get_product( $child_id );

			if ( ! $variation || ! $variation->is_type( 'variation' ) ) {
				continue;
			}

			$attributes = $variation->get_attributes();
			$key        = 'attribute_' . $taxonomy;

			if ( isset( $attributes[ $key ] ) && '' !== $attributes[ $key ] ) {
				$term = get_term_by( 'slug', $attributes[ $key ], $taxonomy );

				if ( $term && ! is_wp_error( $term ) ) {
					$existing[ $term->name ] = true;
				}
			}
		}

		return $existing;
	}

	/**
	 * Ensures a term exists for the generated code.
	 *
	 * @param string $taxonomy Taxonomy.
	 * @param string $code Code.
	 * @return array|WP_Error
	 */
	private function ensure_term( $taxonomy, $code ) {
		$slug = sanitize_title( $code );
		$term = term_exists( $slug, $taxonomy );

		if ( $term && is_array( $term ) ) {
			$term_id = (int) $term['term_id'];
		} elseif ( $term && is_int( $term ) ) {
			$term_id = $term;
		} else {
			$created = wp_insert_term(
				$code,
				$taxonomy,
				array(
					'slug' => $slug,
				)
			);

			if ( is_wp_error( $created ) ) {
				return $created;
			}

			$term_id = isset( $created['term_id'] ) ? (int) $created['term_id'] : 0;
		}

		if ( $term_id < 1 ) {
			return new WP_Error( 'bvcg_term_error', __( 'Unable to create a term for one of the generated codes.', 'bulk-variation-code-generator' ) );
		}

		$term_obj = get_term( $term_id, $taxonomy );

		if ( ! $term_obj || is_wp_error( $term_obj ) ) {
			return new WP_Error( 'bvcg_term_error', __( 'Unable to load the generated term.', 'bulk-variation-code-generator' ) );
		}

		return array(
			'term_id' => $term_id,
			'slug'    => $term_obj->slug,
			'name'    => $term_obj->name,
		);
	}

	/**
	 * Creates a WooCommerce variation.
	 *
	 * @param WC_Product $product Parent product.
	 * @param string     $taxonomy Taxonomy.
	 * @param array      $term Term data.
	 * @param array      $item Item data.
	 * @return int|WP_Error
	 */
	private function create_variation( WC_Product $product, $taxonomy, WP_Term $term, array $item ) {
		$variation = new WC_Product_Variation();
		$variation->set_parent_id( $product->get_id() );
		$variation->set_status( 'publish' );
		$variation->set_attributes(
			array(
				'attribute_' . $taxonomy => $term->slug,
			)
		);

		if ( isset( $item['price'] ) && '' !== $item['price'] ) {
			$variation->set_regular_price( wc_format_decimal( $item['price'] ) );
		}

		if ( isset( $item['sku_prefix'] ) && '' !== $item['sku_prefix'] ) {
			$sku = $item['sku_prefix'] . $item['code'];
			$existing_sku_product_id = wc_get_product_id_by_sku( $sku );

			if ( ! $existing_sku_product_id ) {
				$variation->set_sku( $sku );
			}
		}

		if ( isset( $item['stock_qty'] ) && '' !== $item['stock_qty'] ) {
			$stock_qty = absint( $item['stock_qty'] );
			$variation->set_manage_stock( true );
			$variation->set_stock_quantity( $stock_qty );
			$variation->set_stock_status( $stock_qty > 0 ? 'instock' : 'outofstock' );
		}

		$variation_id = $variation->save();

		if ( ! $variation_id ) {
			return new WP_Error( 'bvcg_variation_failed', __( 'Failed to create one of the variations.', 'bulk-variation-code-generator' ) );
		}

		return (int) $variation_id;
	}

	/**
	 * Syncs attribute terms with the parent product.
	 *
	 * @param WC_Product $product Product.
	 * @param string     $taxonomy Taxonomy.
	 * @param array      $term_ids Term IDs.
	 * @return void
	 */
	private function sync_product_attribute( WC_Product $product, $taxonomy, array $term_ids ) {
		$attributes = $product->get_attributes();
		$attribute  = isset( $attributes[ $taxonomy ] ) && $attributes[ $taxonomy ] instanceof WC_Product_Attribute ? $attributes[ $taxonomy ] : new WC_Product_Attribute();
		$existing   = $attribute->get_options();
		$merged     = array_unique( array_map( 'absint', array_merge( $existing, $term_ids ) ) );
		$attribute->set_id( wc_attribute_taxonomy_id_by_name( $taxonomy ) );
		$attribute->set_name( $taxonomy );
		$attribute->set_options( $merged );
		$attribute->set_position( 0 );
		$attribute->set_visible( true );
		$attribute->set_variation( true );

		$attributes[ $taxonomy ] = $attribute;
		$product->set_attributes( $attributes );
		$product->save();
	}

	/**
	 * Ensures all terms exist for a set of items and returns their IDs.
	 *
	 * @param string $taxonomy Taxonomy.
	 * @param array  $items Items.
	 * @return array
	 */
	private function prime_terms_for_items( $taxonomy, array $items ) {
		$term_ids = array();

		foreach ( $items as $item ) {
			$term = $this->ensure_term( $taxonomy, $item['code'] );

			if ( is_wp_error( $term ) ) {
				return $term;
			}

			$term_ids[] = (int) $term['term_id'];
		}

		return array_values( array_unique( array_filter( $term_ids ) ) );
	}

	/**
	 * Imports uploaded images and maps them by normalized base filename.
	 *
	 * @param array $items Generated items.
	 * @return array|WP_Error
	 */
	private function prepare_uploaded_images( array $items ) {
		if ( empty( $_FILES['variation_images'] ) || ! is_array( $_FILES['variation_images'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			return array(
				'map'   => array(),
				'stats' => array(
					'selected'   => 0,
					'matched'    => 0,
					'unmatched'   => 0,
					'errors'      => array(),
				),
			);
		}

		$this->load_media_upload_dependencies();

		$files = $this->normalize_uploaded_files_array( $_FILES['variation_images'] ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
		$image_map = array();
		$errors = array();
		$selected = 0;
		$matched = 0;

		foreach ( $files as $file ) {
			if ( empty( $file['name'] ) ) {
				continue;
			}

			$selected++;
			$file['name'] = sanitize_file_name( wp_basename( $file['name'] ) );

			$attachment_id = $this->sideload_image_to_media_library( $file );

			if ( is_wp_error( $attachment_id ) ) {
				$errors[] = $attachment_id->get_error_message();
				continue;
			}

			$code_key = $this->normalize_code_key( pathinfo( $file['name'], PATHINFO_FILENAME ) );

			if ( '' === $code_key ) {
				$errors[] = sprintf(
					/* translators: %s: file name. */
					__( 'Could not match image file %s to a variation code.', 'bulk-variation-code-generator' ),
					$file['name']
				);
				wp_delete_attachment( (int) $attachment_id, true );
				continue;
			}

			if ( isset( $image_map[ $code_key ] ) ) {
				$errors[] = sprintf(
					/* translators: %s: file name. */
					__( 'Duplicate image mapping detected for %s. The first match was kept.', 'bulk-variation-code-generator' ),
					$file['name']
				);
				wp_delete_attachment( (int) $attachment_id, true );
				continue;
			}

			$image_map[ $code_key ] = (int) $attachment_id;
			$matched++;
		}

		$unmatched = max( 0, $selected - $matched );

		return array(
			'map'   => $image_map,
			'stats' => array(
				'selected' => $selected,
				'matched'  => $matched,
				'unmatched' => $unmatched,
				'errors'   => $errors,
			),
		);
	}

	/**
	 * Flattens the $_FILES array for multiple upload inputs.
	 *
	 * @param array $files Upload array.
	 * @return array
	 */
	private function normalize_uploaded_files_array( array $files ) {
		$normalized = array();

		if ( empty( $files['name'] ) || ! is_array( $files['name'] ) ) {
			return $normalized;
		}

		foreach ( $files['name'] as $index => $name ) {
			$normalized[] = array(
				'name'     => $name,
				'type'     => isset( $files['type'][ $index ] ) ? $files['type'][ $index ] : '',
				'tmp_name' => isset( $files['tmp_name'][ $index ] ) ? $files['tmp_name'][ $index ] : '',
				'error'    => isset( $files['error'][ $index ] ) ? (int) $files['error'][ $index ] : UPLOAD_ERR_NO_FILE,
				'size'     => isset( $files['size'][ $index ] ) ? (int) $files['size'][ $index ] : 0,
			);
		}

		return $normalized;
	}

	/**
	 * Loads WordPress upload helpers.
	 *
	 * @return void
	 */
	private function load_media_upload_dependencies() {
		if ( ! function_exists( 'media_handle_sideload' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/media.php';
			require_once ABSPATH . 'wp-admin/includes/image.php';
		}
	}

	/**
	 * Imports an uploaded image into the media library.
	 *
	 * @param array $file File array.
	 * @return int|WP_Error
	 */
	private function sideload_image_to_media_library( array $file ) {
		if ( ! isset( $file['error'] ) || UPLOAD_ERR_OK !== (int) $file['error'] ) {
			return new WP_Error( 'bvcg_upload_error', __( 'One of the image uploads failed.', 'bulk-variation-code-generator' ) );
		}

		$filetype = wp_check_filetype_and_ext( $file['tmp_name'], $file['name'] );

		if ( empty( $filetype['type'] ) || 0 !== strpos( (string) $filetype['type'], 'image/' ) ) {
			return new WP_Error( 'bvcg_invalid_image', __( 'Only image files can be uploaded.', 'bulk-variation-code-generator' ) );
		}

		$attachment_id = media_handle_sideload( $file, 0 );

		if ( is_wp_error( $attachment_id ) ) {
			return $attachment_id;
		}

		return (int) $attachment_id;
	}

	/**
	 * Normalizes a variation code or filename into a matching key.
	 *
	 * @param string $value Raw value.
	 * @return string
	 */
	private function normalize_code_key( $value ) {
		return sanitize_title( preg_replace( '/\.[^.]+$/', '', (string) $value ) );
	}

	/**
	 * Returns variation IDs keyed by generated code.
	 *
	 * @param WC_Product $product Product.
	 * @param string     $taxonomy Attribute taxonomy.
	 * @return array
	 */
	private function get_existing_variations( WC_Product $product, $taxonomy ) {
		$existing = array();

		foreach ( $product->get_children() as $child_id ) {
			$variation = wc_get_product( $child_id );

			if ( ! $variation || ! $variation->is_type( 'variation' ) ) {
				continue;
			}

			$attributes = $variation->get_attributes();
			$key        = sanitize_title( 'attribute_' . $taxonomy );

			if ( isset( $attributes[ $taxonomy ] ) && '' !== $attributes[ $taxonomy ] ) {
				$term = get_term_by( 'slug', $attributes[ $taxonomy ], $taxonomy );

				if ( $term && ! is_wp_error( $term ) ) {
					$existing[ $term->name ] = (int) $variation->get_id();
				}
			} elseif ( isset( $attributes[ $key ] ) && '' !== $attributes[ $key ] ) {
				$term = get_term_by( 'slug', $attributes[ $key ], $taxonomy );

				if ( $term && ! is_wp_error( $term ) ) {
					$existing[ $term->name ] = (int) $variation->get_id();
				}
			}
		}

		return $existing;
	}

	/**
	 * Finds the attached image for a generated code.
	 *
	 * @param string $code Code.
	 * @param array  $image_map Image map.
	 * @return int
	 */
	private function get_image_attachment_id_for_code( $code, array $image_map ) {
		$key = $this->normalize_code_key( $code );

		return isset( $image_map[ $key ] ) ? (int) $image_map[ $key ] : 0;
	}

	/**
	 * Assigns an image to a variation if it does not already have one.
	 *
	 * @param int  $variation_id Variation ID.
	 * @param int  $attachment_id Attachment ID.
	 * @param bool $overwrite Whether to replace an existing image.
	 * @return array|WP_Error
	 */
	private function assign_variation_image( $variation_id, $attachment_id, $overwrite = false, array $seo_template = array() ) {
		$variation_id  = absint( $variation_id );
		$attachment_id = absint( $attachment_id );

		if ( $variation_id < 1 || $attachment_id < 1 ) {
			return new WP_Error( 'bvcg_invalid_image_assignment', __( 'Could not assign an image to the variation.', 'bulk-variation-code-generator' ) );
		}

		$variation = wc_get_product( $variation_id );

		if ( ! $variation || ! $variation->is_type( 'variation' ) ) {
			return new WP_Error( 'bvcg_invalid_variation', __( 'The variation could not be loaded for image assignment.', 'bulk-variation-code-generator' ) );
		}

		$current_image_id = (int) $variation->get_image_id();

		if ( $current_image_id > 0 && ! $overwrite ) {
			return array(
				'assigned' => false,
				'reason'   => 'existing_image',
			);
		}

		if ( $current_image_id === $attachment_id ) {
			return array(
				'assigned' => false,
				'reason'   => 'same_image',
			);
		}

		$this->apply_image_seo_metadata( $attachment_id, $variation, $seo_template );

		$variation->set_image_id( $attachment_id );
		$variation->save();

		return array(
			'assigned' => true,
			'reason'   => 'updated',
		);
	}

	/**
	 * Updates attachment SEO metadata for a variation image.
	 *
	 * @param int                 $attachment_id Attachment ID.
	 * @param WC_Product_Variation $variation Variation object.
	 * @param array               $seo_template SEO template values.
	 * @return void
	 */
	private function apply_image_seo_metadata( $attachment_id, WC_Product_Variation $variation, array $seo_template = array() ) {
		$attachment_id = absint( $attachment_id );

		if ( $attachment_id < 1 ) {
			return;
		}

		$product = wc_get_product( $variation->get_parent_id() );

		if ( ! $product ) {
			return;
		}

		$taxonomy = $this->get_variation_taxonomy( $variation );
		$code     = $taxonomy ? $variation->get_attribute( $taxonomy ) : '';

		if ( '' === $code ) {
			$code = $variation->get_name();
		}

		$product_name = $product->get_name();
		$attribute_value = $code;
		$attribute_name = $taxonomy ? wc_attribute_label( $taxonomy, $product ) : '';

		$defaults = array(
			'title'       => '{product_name} - {variation_code}',
			'alt'         => '{product_name} variation {variation_code}',
			'caption'     => '{product_name} {variation_code}',
			'description' => '{product_name} image for variation {variation_code}',
		);

		$templates = wp_parse_args(
			$seo_template,
			$defaults
		);

		$replacements = array(
			'{product_name}'    => $product_name,
			'{variation_code}'  => $code,
			'{attribute_name}'  => $attribute_name,
			'{attribute_value}' => $attribute_value,
		);

		$meta = array(
			'title'       => $this->resolve_seo_template_value( $templates['title'], $replacements ),
			'alt'         => $this->resolve_seo_template_value( $templates['alt'], $replacements ),
			'caption'     => $this->resolve_seo_template_value( $templates['caption'], $replacements ),
			'description' => $this->resolve_seo_template_value( $templates['description'], $replacements, true ),
		);

		$meta = apply_filters( 'bvcg_image_seo_meta', $meta, $product, $variation, $code );

		$title       = isset( $meta['title'] ) ? sanitize_text_field( (string) $meta['title'] ) : '';
		$alt         = isset( $meta['alt'] ) ? sanitize_text_field( (string) $meta['alt'] ) : '';
		$caption     = isset( $meta['caption'] ) ? sanitize_text_field( (string) $meta['caption'] ) : '';
		$description = isset( $meta['description'] ) ? sanitize_textarea_field( (string) $meta['description'] ) : '';

		wp_update_post(
			array(
				'ID'           => $attachment_id,
				'post_title'   => $title,
				'post_excerpt' => $caption,
				'post_content' => $description,
			)
		);

		update_post_meta( $attachment_id, '_wp_attachment_image_alt', $alt );
	}

	/**
	 * Resolves a template string into a final SEO value.
	 *
	 * @param string $template Template string.
	 * @param array  $replacements Placeholder replacements.
	 * @param bool   $multiline Whether to preserve line breaks.
	 * @return string
	 */
	private function resolve_seo_template_value( $template, array $replacements, $multiline = false ) {
		$template = trim( wp_strip_all_tags( (string) $template ) );

		if ( '' === $template ) {
			return '';
		}

		$value = strtr( $template, $replacements );
		$value = $multiline ? sanitize_textarea_field( $value ) : sanitize_text_field( $value );

		return $value;
	}

	/**
	 * Normalizes SEO template values from the request payload.
	 *
	 * @param array $payload Raw payload.
	 * @return array
	 */
	private function normalize_seo_template( array $payload ) {
		$defaults = array(
			'title'       => '{product_name} - {variation_code}',
			'alt'         => '{product_name} variation {variation_code}',
			'caption'     => '{product_name} {variation_code}',
			'description' => '{product_name} image for variation {variation_code}',
		);

		return array(
			'title'       => isset( $payload['seo_title'] ) && '' !== trim( (string) $payload['seo_title'] ) ? sanitize_text_field( wp_unslash( $payload['seo_title'] ) ) : $defaults['title'],
			'alt'         => isset( $payload['seo_alt'] ) && '' !== trim( (string) $payload['seo_alt'] ) ? sanitize_text_field( wp_unslash( $payload['seo_alt'] ) ) : $defaults['alt'],
			'caption'     => isset( $payload['seo_caption'] ) && '' !== trim( (string) $payload['seo_caption'] ) ? sanitize_text_field( wp_unslash( $payload['seo_caption'] ) ) : $defaults['caption'],
			'description' => isset( $payload['seo_description'] ) && '' !== trim( (string) $payload['seo_description'] ) ? sanitize_textarea_field( wp_unslash( $payload['seo_description'] ) ) : $defaults['description'],
		);
	}

	/**
	 * Returns the attribute taxonomy used by a variation.
	 *
	 * @param WC_Product_Variation $variation Variation object.
	 * @return string
	 */
	private function get_variation_taxonomy( WC_Product_Variation $variation ) {
		$attributes = $variation->get_variation_attributes( false );

		foreach ( array_keys( $attributes ) as $key ) {
			if ( 0 === strpos( $key, 'attribute_' ) ) {
				return substr( $key, 10 );
			}
		}

		return '';
	}
}
