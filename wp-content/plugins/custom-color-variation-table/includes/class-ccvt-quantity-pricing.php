<?php
/**
 * Quantity tier pricing service.
 *
 * @package CustomColorVariationTable
 */

defined( 'ABSPATH' ) || exit;

class CCVT_Quantity_Pricing {

	public function __construct() {
		add_action( 'woocommerce_before_calculate_totals', array( $this, 'apply_cart_prices' ), 20 );
	}

	public static function get_tiers_for_product( $product ) {
		if ( ! $product instanceof WC_Product ) {
			return array();
		}

		$parent_id = $product->is_type( 'variation' ) ? $product->get_parent_id() : $product->get_id();
		$parent    = $parent_id ? wc_get_product( $parent_id ) : false;

		if ( ! $parent || ! $parent->is_type( 'variable' ) ) {
			return array();
		}

		$raw = get_post_meta( $parent_id, '_yao_quantity_price_tiers', true );
		$raw = is_array( $raw ) ? $raw : json_decode( (string) $raw, true );

		return self::normalize_tiers( is_array( $raw ) ? $raw : array() );
	}

	public static function get_price_for_quantity( array $tiers, $quantity ) {
		$quantity = max( 1, absint( $quantity ) );

		foreach ( $tiers as $tier ) {
			if ( $quantity < $tier['min'] ) {
				continue;
			}

			if ( null === $tier['max'] || $quantity <= $tier['max'] ) {
				return $tier['price'];
			}
		}

		return null;
	}

	public function apply_cart_prices( $cart ) {
		if ( ! $cart instanceof WC_Cart || ( is_admin() && ! wp_doing_ajax() ) ) {
			return;
		}

		foreach ( $cart->get_cart() as $cart_item ) {
			$product = isset( $cart_item['data'] ) ? $cart_item['data'] : false;

			if ( ! $product instanceof WC_Product_Variation ) {
				continue;
			}

			$tiers = self::get_tiers_for_product( $product );
			$price = self::get_price_for_quantity( $tiers, $cart_item['quantity'] ?? 0 );

			if ( null !== $price ) {
				$product->set_price( $price );
			}
		}
	}

	private static function normalize_tiers( array $tiers ) {
		$normalized = array();

		foreach ( $tiers as $tier ) {
			if ( ! is_array( $tier ) || '' === trim( (string) ( $tier['price'] ?? '' ) ) ) {
				continue;
			}

			$min     = absint( $tier['min'] ?? 0 );
			$max_raw = strtolower( trim( (string) ( $tier['max'] ?? '' ) ) );
			$max     = in_array( $max_raw, array( '', 'unlimited', '+' ), true ) ? null : absint( $max_raw );
			$price   = wc_format_decimal( $tier['price'] );

			if ( $min < 1 || ( null !== $max && $max < $min ) || (float) $price < 0 ) {
				continue;
			}

			$info = sanitize_textarea_field( $tier['info'] ?? '' );

			$normalized[] = array(
				'min'   => $min,
				'max'   => $max,
				'price' => $price,
				'info'  => '' === $info ? null : $info,
			);
		}

		usort( $normalized, static function ( $a, $b ) {
			return $a['min'] <=> $b['min'];
		} );

		$valid = array();
		$next_min = 1;

		foreach ( $normalized as $tier ) {
			if ( $tier['min'] !== $next_min ) {
				return array();
			}

			$valid[] = $tier;
			if ( null === $tier['max'] ) {
				return count( $valid ) === count( $normalized ) ? $valid : array();
			}

			$next_min = $tier['max'] + 1;
		}

		return ! empty( $valid ) && null === $valid[ count( $valid ) - 1 ]['max'] ? $valid : array();
	}
}
