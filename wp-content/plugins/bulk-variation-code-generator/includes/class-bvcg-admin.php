<?php
/**
 * Admin UI controller.
 *
 * @package BulkVariationCodeGenerator
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class BVCG_Admin {

	/**
	 * Generator service.
	 *
	 * @var BVCG_Generator
	 */
	private $generator;

	/**
	 * Constructor.
	 *
	 * @param BVCG_Generator $generator Generator service.
	 */
	public function __construct( BVCG_Generator $generator ) {
		$this->generator = $generator;

		add_filter( 'woocommerce_product_data_tabs', array( $this, 'add_product_data_tab' ) );
		add_action( 'woocommerce_product_data_panels', array( $this, 'render_product_data_panel' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	/**
	 * Adds the product data tab.
	 *
	 * @param array $tabs Tabs.
	 * @return array
	 */
	public function add_product_data_tab( array $tabs ) {
		$tabs['bvcg_bulk_variation_generator'] = array(
			'label'    => __( 'Bulk Variation Generator', 'bulk-variation-code-generator' ),
			'target'   => 'bvcg_bulk_variation_generator',
			'class'    => array( 'show_if_variable' ),
			'priority' => 90,
		);

		return $tabs;
	}

	/**
	 * Renders the panel content.
	 *
	 * @return void
	 */
	public function render_product_data_panel() {
		global $post;

		$product_id = isset( $post->ID ) ? absint( $post->ID ) : 0;
		$attributes = $this->get_global_attributes();
		$nonce      = wp_create_nonce( 'bvcg_generate_variations' );
		?>
		<div id="bvcg_bulk_variation_generator" class="panel woocommerce_options_panel hidden">
			<input type="hidden" id="bvcg_product_id" value="<?php echo esc_attr( $product_id ); ?>">
			<input type="hidden" id="bvcg_nonce" value="<?php echo esc_attr( $nonce ); ?>">
			<div class="bvcg-panel">
				<p class="bvcg-notice"><?php esc_html_e( 'This tool works with variable products only. Generate missing variations without deleting existing ones.', 'bulk-variation-code-generator' ); ?></p>

				<p class="form-field bvcg-field">
					<label for="bvcg_attribute"><?php esc_html_e( 'Attribute', 'bulk-variation-code-generator' ); ?></label>
					<select id="bvcg_attribute">
						<option value=""><?php esc_html_e( 'Select Attribute', 'bulk-variation-code-generator' ); ?></option>
						<?php foreach ( $attributes as $attribute ) : ?>
							<option value="<?php echo esc_attr( $attribute['taxonomy'] ); ?>"><?php echo esc_html( $attribute['label'] ); ?></option>
						<?php endforeach; ?>
					</select>
					<span class="description"><?php esc_html_e( 'Choose the global attribute that should receive the generated codes.', 'bulk-variation-code-generator' ); ?></span>
				</p>

				<div class="bvcg-grid">
					<p class="form-field bvcg-field">
						<label for="bvcg_prefix"><?php esc_html_e( 'Code Prefix', 'bulk-variation-code-generator' ); ?></label>
						<input type="text" id="bvcg_prefix" placeholder="BR-EN-" value="BR-EN-">
					</p>

					<p class="form-field bvcg-field">
						<label for="bvcg_start"><?php esc_html_e( 'Starting Number', 'bulk-variation-code-generator' ); ?></label>
						<input type="number" id="bvcg_start" min="1" value="1">
					</p>

					<p class="form-field bvcg-field">
						<label for="bvcg_end"><?php esc_html_e( 'Ending Number', 'bulk-variation-code-generator' ); ?></label>
						<input type="number" id="bvcg_end" min="1" value="99">
					</p>

					<p class="form-field bvcg-field">
						<label for="bvcg_digits"><?php esc_html_e( 'Digits', 'bulk-variation-code-generator' ); ?></label>
						<select id="bvcg_digits">
							<option value="2"><?php esc_html_e( '2', 'bulk-variation-code-generator' ); ?></option>
							<option value="3" selected="selected"><?php esc_html_e( '3', 'bulk-variation-code-generator' ); ?></option>
							<option value="4"><?php esc_html_e( '4', 'bulk-variation-code-generator' ); ?></option>
						</select>
					</p>

					<p class="form-field bvcg-field">
						<label for="bvcg_price"><?php esc_html_e( 'Price', 'bulk-variation-code-generator' ); ?></label>
						<input type="text" id="bvcg_price" placeholder="2">
					</p>

					<p class="form-field bvcg-field">
						<label for="bvcg_sku_prefix"><?php esc_html_e( 'SKU Prefix', 'bulk-variation-code-generator' ); ?></label>
						<input type="text" id="bvcg_sku_prefix" placeholder="TILA-">
					</p>

					<p class="form-field bvcg-field">
						<label for="bvcg_stock_qty"><?php esc_html_e( 'Stock Quantity', 'bulk-variation-code-generator' ); ?></label>
						<input type="number" id="bvcg_stock_qty" min="0" value="100">
					</p>
				</div>

				<hr>

				<div class="bvcg-import">
					<p class="form-field bvcg-field">
						<label for="bvcg_csv_file"><?php esc_html_e( 'CSV Import', 'bulk-variation-code-generator' ); ?></label>
						<input type="file" id="bvcg_csv_file" accept=".csv,text/csv">
						<span class="description"><?php esc_html_e( 'Optional. CSV columns can include Prefix, Start, End, Price, Digits, SKU Prefix, Stock Quantity.', 'bulk-variation-code-generator' ); ?></span>
					</p>

					<textarea id="bvcg_csv_text" class="bvcg-hidden-textarea" aria-hidden="true"></textarea>
				</div>

				<div class="bvcg-image-import">
					<p class="form-field bvcg-field">
						<label for="bvcg_image_files"><?php esc_html_e( 'Bulk Variation Images', 'bulk-variation-code-generator' ); ?></label>
						<input type="file" id="bvcg_image_files" name="variation_images[]" accept="image/*" multiple>
							<span class="description"><?php esc_html_e( 'Match each image filename to a generated code. Example: BR-EN-001.jpg will attach to BR-EN-001 and auto-fill image SEO fields from the product and variation code. Each image can be up to 10 GB.', 'bulk-variation-code-generator' ); ?></span>
					</p>

					<div class="bvcg-seo-template">
						<h3><?php esc_html_e( 'Image SEO Template', 'bulk-variation-code-generator' ); ?></h3>
						<p class="description"><?php esc_html_e( 'Use these placeholders: {product_name}, {variation_code}, {attribute_name}, {attribute_value}', 'bulk-variation-code-generator' ); ?></p>

						<div class="bvcg-grid">
							<p class="form-field bvcg-field">
								<label for="bvcg_seo_title"><?php esc_html_e( 'Title Template', 'bulk-variation-code-generator' ); ?></label>
								<input type="text" id="bvcg_seo_title" value="{product_name} - {variation_code}">
							</p>

							<p class="form-field bvcg-field">
								<label for="bvcg_seo_alt"><?php esc_html_e( 'Alt Template', 'bulk-variation-code-generator' ); ?></label>
								<input type="text" id="bvcg_seo_alt" value="{product_name} variation {variation_code}">
							</p>

							<p class="form-field bvcg-field">
								<label for="bvcg_seo_caption"><?php esc_html_e( 'Caption Template', 'bulk-variation-code-generator' ); ?></label>
								<input type="text" id="bvcg_seo_caption" value="{product_name} {variation_code}">
							</p>

							<p class="form-field bvcg-field">
								<label for="bvcg_seo_description"><?php esc_html_e( 'Description Template', 'bulk-variation-code-generator' ); ?></label>
								<input type="text" id="bvcg_seo_description" value="{product_name} image for variation {variation_code}">
							</p>
						</div>
					</div>

					<div id="bvcg_image_preview" class="bvcg-image-preview">
						<p><?php esc_html_e( 'No images selected.', 'bulk-variation-code-generator' ); ?></p>
					</div>
				</div>

				<div class="bvcg-preview-wrap">
					<h3><?php esc_html_e( 'Preview', 'bulk-variation-code-generator' ); ?></h3>
					<div id="bvcg_preview" class="bvcg-preview">
						<p><?php esc_html_e( 'Select an attribute and enter a range to preview generated codes.', 'bulk-variation-code-generator' ); ?></p>
					</div>
				</div>

				<div class="bvcg-actions">
					<button type="button" class="button button-primary button-large" id="bvcg_generate_button"><?php esc_html_e( 'Generate Variations', 'bulk-variation-code-generator' ); ?></button>
					<button type="button" class="button" id="bvcg_resume_button" hidden><?php esc_html_e( 'Resume Generation', 'bulk-variation-code-generator' ); ?></button>
					<span id="bvcg_status" class="bvcg-status" aria-live="polite"></span>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Enqueues admin assets on product edit screens.
	 *
	 * @param string $hook Current hook.
	 * @return void
	 */
	public function enqueue_assets( $hook ) {
		if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
			return;
		}

		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

		if ( ! $screen || 'product' !== $screen->post_type ) {
			return;
		}

		$script_path = BVCG_PATH . 'assets/js/admin.js';
		$style_path  = BVCG_PATH . 'assets/css/admin.css';

		wp_enqueue_style(
			'bvcg-admin',
			BVCG_URL . 'assets/css/admin.css',
			array(),
			file_exists( $style_path ) ? (string) filemtime( $style_path ) : BVCG_VERSION
		);

		wp_enqueue_script(
			'bvcg-admin',
			BVCG_URL . 'assets/js/admin.js',
			array( 'jquery' ),
			file_exists( $script_path ) ? (string) filemtime( $script_path ) : BVCG_VERSION,
			true
		);

		wp_localize_script(
			'bvcg-admin',
			'bvcgData',
			array(
				'ajaxUrl'     => admin_url( 'admin-ajax.php' ),
				'nonce'       => wp_create_nonce( 'bvcg_generate_variations' ),
				'batchSize'   => BVCG_Generator::DEFAULT_BATCH_SIZE,
				'strings'     => array(
					'loading'           => __( 'Generating...', 'bulk-variation-code-generator' ),
					'completed'         => __( 'Completed', 'bulk-variation-code-generator' ),
					'error'             => __( 'An error occurred.', 'bulk-variation-code-generator' ),
					'noPreview'         => __( 'Select an attribute and enter a range to preview generated codes.', 'bulk-variation-code-generator' ),
					'saveFirst'         => __( 'Save the product before generating variations.', 'bulk-variation-code-generator' ),
						'noImagesSelected'  => __( 'No images selected.', 'bulk-variation-code-generator' ),
						'imagesSelected'    => __( 'selected images', 'bulk-variation-code-generator' ),
						'matchedImages'     => __( 'matched by filename', 'bulk-variation-code-generator' ),
						'imageTooLarge'     => __( 'Each image must be 10 GB or smaller.', 'bulk-variation-code-generator' ),
					),
				)
				);
			}

	/**
	 * Returns global product attributes.
	 *
	 * @return array
	 */
	private function get_global_attributes() {
		$attributes = array();
		$taxonomies = wc_get_attribute_taxonomies();

		if ( empty( $taxonomies ) ) {
			return $attributes;
		}

		foreach ( $taxonomies as $taxonomy ) {
			$attributes[] = array(
				'taxonomy' => wc_attribute_taxonomy_name( $taxonomy->attribute_name ),
				'label'    => $taxonomy->attribute_label ? $taxonomy->attribute_label : $taxonomy->attribute_name,
			);
		}

		return $attributes;
	}
}
