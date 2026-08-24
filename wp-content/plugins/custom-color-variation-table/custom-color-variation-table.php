<?php
/**
 * Plugin Name: Custom Color Variation Table
 * Description: Replaces WooCommerce variable product color dropdown with a bulk quantity table on the product page.
 * Version: 1.0.0
 * Author: Faisal Nur
 * Text Domain: custom-color-variation-table
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Custom_Color_Variation_Table {
    private $allowed_quantity_steps = array( 1, 3 );

    public function __construct() {
        add_action( 'plugins_loaded', array( $this, 'init' ), 20 );
    }

    public function init() {
        add_filter( 'woocommerce_locate_template', array( $this, 'locate_template' ), 10, 3 );
        add_filter( 'body_class', array( $this, 'add_body_class' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_cart_assets' ) );
        add_action( 'wp', array( $this, 'maybe_remove_single_product_summary_parts' ) );
        add_action( 'ccvt_render_related_product_carousels', array( $this, 'render_related_product_carousels' ) );
        add_filter( 'woocommerce_quantity_input_args', array( $this, 'filter_quantity_input_args' ), 10, 2 );
        add_filter( 'woocommerce_cart_item_quantity', array( $this, 'render_cart_item_quantity' ), 10, 3 );
        add_action( 'woocommerce_product_options_inventory_product_data', array( $this, 'render_quantity_step_field' ) );
        add_action( 'woocommerce_admin_process_product_object', array( $this, 'save_quantity_step_field' ) );
        add_action( 'wp_ajax_ccvt_add_to_cart', array( $this, 'ajax_add_to_cart' ) );
        add_action( 'wp_ajax_nopriv_ccvt_add_to_cart', array( $this, 'ajax_add_to_cart' ) );
        add_filter( 'woocommerce_grouped_product_list_column_quantity', array( $this, 'render_grouped_child_quantity_column' ), 10, 2 );
        add_action( 'woocommerce_before_add_to_cart_form', array( $this, 'render_grouped_notices' ) );
    }

    private function normalize_quantity_step( $step ) {
        $step = absint( $step );

        if ( ! in_array( $step, $this->allowed_quantity_steps, true ) ) {
            return 1;
        }

        return $step;
    }

    private function normalize_quantity_to_step( $quantity, $step ) {
        $quantity = absint( $quantity );
        $step     = $this->normalize_quantity_step( $step );

        if ( 1 === $step ) {
            return $quantity;
        }

        return (int) floor( $quantity / $step ) * $step;
    }

    private function get_quantity_step_for_product( $product ) {
        if ( ! $product instanceof WC_Product ) {
            return 1;
        }

        $quantity_step = get_post_meta( $product->get_id(), '_ccvt_quantity_step', true );

        if ( '' !== $quantity_step && null !== $quantity_step ) {
            return $this->normalize_quantity_step( $quantity_step );
        }

        if ( $product->is_type( 'variation' ) ) {
            $parent_id = $product->get_parent_id();
            if ( $parent_id ) {
                return $this->normalize_quantity_step( get_post_meta( $parent_id, '_ccvt_quantity_step', true ) );
            }
        }

        return 1;
    }

    public function filter_quantity_input_args( $args, $product ) {
        if ( ! $product instanceof WC_Product ) {
            return $args;
        }

        $quantity_step = $this->get_quantity_step_for_product( $product );

        if ( $quantity_step < 1 ) {
            return $args;
        }

        $args['step'] = $quantity_step;

        if ( ! isset( $args['min_value'] ) || $args['min_value'] < 0 ) {
            $args['min_value'] = 0;
        }

        return $args;
    }

    public function render_cart_item_quantity( $product_quantity, $cart_item_key, $cart_item ) {
        if ( ! is_cart() || empty( $cart_item['data'] ) || ! $cart_item['data'] instanceof WC_Product ) {
            return $product_quantity;
        }

        $product = $cart_item['data'];

        if ( $product->is_sold_individually() ) {
            return $product_quantity;
        }

        $quantity_step = $this->get_quantity_step_for_product( $product );
        $max_qty       = $product->get_max_purchase_quantity();
        $min_qty       = 0;
        $quantity      = absint( $cart_item['quantity'] );
        $disabled      = ! $product->is_purchasable() || ! $product->is_in_stock();

        ob_start();
        ?>
        <div class="ccvt-variation-quantity">
            <div class="ccvt-qty-control ccvt-cart-qty-control">
                <button type="button" class="ccvt-qty-button ccvt-qty-decrement" aria-label="<?php esc_attr_e( 'Decrease quantity', 'custom-color-variation-table' ); ?>" <?php echo $disabled ? 'disabled' : ''; ?>>-</button>
                <input
                    type="number"
                    class="ccvt-variation-qty ccvt-cart-qty-input"
                    name="<?php echo esc_attr( "cart[{$cart_item_key}][qty]" ); ?>"
                    min="<?php echo esc_attr( $min_qty ); ?>"
                    step="<?php echo esc_attr( $quantity_step ); ?>"
                    value="<?php echo esc_attr( $quantity ); ?>"
                    data-step="<?php echo esc_attr( $quantity_step ); ?>"
                    data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>"
                    data-max-qty="<?php echo esc_attr( $max_qty ); ?>"
                    <?php echo $disabled ? 'disabled' : ''; ?>
                    aria-label="<?php echo esc_attr( sprintf( __( 'Quantity for %s', 'custom-color-variation-table' ), $product->get_name() ) ); ?>"
                />
                <button type="button" class="ccvt-qty-button ccvt-qty-increment" aria-label="<?php esc_attr_e( 'Increase quantity', 'custom-color-variation-table' ); ?>" <?php echo $disabled ? 'disabled' : ''; ?>>+</button>
            </div>
        </div>
        <?php

        return ob_get_clean();
    }

    public function render_quantity_step_field() {
        global $product_object;

        if ( ! $product_object instanceof WC_Product ) {
            return;
        }

        $options = array();
        foreach ( $this->allowed_quantity_steps as $step ) {
            $options[ (string) $step ] = sprintf( __( '%d pcs', 'custom-color-variation-table' ), $step );
        }

        woocommerce_wp_select(
            array(
                'id'          => '_ccvt_quantity_step',
                'label'       => __( 'Quantity step', 'custom-color-variation-table' ),
                'description' => __( 'Controls how many pieces are added when using the +/- buttons in the quantity table.', 'custom-color-variation-table' ),
                'desc_tip'    => true,
                'options'     => $options,
                'value'       => (string) $this->get_quantity_step_for_product( $product_object ),
            )
        );
    }

    public function save_quantity_step_field( $product ) {
        if ( ! $product instanceof WC_Product || ! isset( $_POST['_ccvt_quantity_step'] ) ) {
            return;
        }

        $quantity_step = $this->normalize_quantity_step( wp_unslash( $_POST['_ccvt_quantity_step'] ) );
        $product->update_meta_data( '_ccvt_quantity_step', $quantity_step );
    }

    public function disable_variation_swatches_hooks() {
        if ( ! function_exists( 'woo_variation_swatches' ) ) {
            return;
        }

        $frontend = woo_variation_swatches()->get_frontend();
        if ( ! $frontend ) {
            return;
        }

        $product_page = $frontend->get_product_page();
        if ( ! $product_page ) {
            return;
        }

        remove_filter( 'woocommerce_dropdown_variation_attribute_options_html', array( $product_page, 'dropdown' ), 20, 2 );
        remove_action( 'woocommerce_before_variations_form', array( $product_page, 'before_variations_form' ) );
        remove_action( 'woocommerce_after_variations_form', array( $product_page, 'after_variations_form' ) );
        remove_action( 'wp_enqueue_scripts', array( $product_page, 'enqueue_scripts' ) );
        remove_filter( 'woocommerce_available_variation', array( $product_page, 'add_variation_data' ), 10, 3 );
        remove_filter( 'woocommerce_variation_is_active', array( $product_page, 'disable_out_of_stock_item' ), 10, 2 );
        remove_action( 'wc_ajax_woo_get_all_variations', array( $product_page, 'get_all_variations' ) );
        remove_filter( 'woocommerce_get_script_data', array( $product_page, 'add_to_cart_variation_params' ), 10, 2 );
        remove_filter( 'woocommerce_ajax_variation_threshold', array( $product_page, 'ajax_variation_threshold' ) );
        remove_filter( 'woocommerce_variable_children_args', array( $product_page, 'variable_children_args' ), 10, 3 );
    }

    public function locate_template( $template, $template_name, $template_path ) {
        if ( in_array( $template_name, array( 'single-product/add-to-cart/variable.php', 'single-product/add-to-cart/grouped.php', 'single-product/related.php' ), true ) ) {
            $plugin_template = plugin_dir_path( __FILE__ ) . 'templates/' . $template_name;

            if ( file_exists( $plugin_template ) ) {
                $template = $plugin_template;
            }
        }

        return $template;
    }

    public function maybe_remove_single_product_summary_parts() {
        if ( ! is_product() ) {
            return;
        }

        $product_id = get_queried_object_id();
        $product    = $product_id ? wc_get_product( $product_id ) : null;

        if ( ! $product instanceof WC_Product_Variable ) {
            return;
        }

        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
        remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
    }

    public function add_body_class( $classes ) {
        if ( ! is_product() ) {
            return $classes;
        }

        $product_id = get_queried_object_id();
        $product    = $product_id ? wc_get_product( $product_id ) : null;

        if ( $product instanceof WC_Product_Variable ) {
            $classes[] = 'ccvt-hide-summary-title-price';
            $classes[] = 'ccvt-gallery-sync';
        }

        return $classes;
    }

    private function get_variable_product_ids( $exclude_ids = array(), $category_ids = array(), $category_operator = 'IN', $limit = 8 ) {
        $exclude_ids = array_values( array_unique( array_filter( array_map( 'absint', (array) $exclude_ids ) ) ) );
        $category_ids = array_values( array_unique( array_filter( array_map( 'absint', (array) $category_ids ) ) ) );

        $tax_query = array(
            array(
                'taxonomy' => 'product_type',
                'field'    => 'slug',
                'terms'    => array( 'variable' ),
            ),
        );

        if ( ! empty( $category_ids ) ) {
            $tax_query[] = array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $category_ids,
                'operator' => $category_operator,
            );
        }

        $query = new WP_Query(
            array(
                'post_type'           => 'product',
                'post_status'         => 'publish',
                'posts_per_page'      => absint( $limit ),
                'fields'              => 'ids',
                'no_found_rows'       => true,
                'ignore_sticky_posts' => true,
                'orderby'             => array(
                    'menu_order' => 'ASC',
                    'title'      => 'ASC',
                ),
                'post__not_in'        => $exclude_ids,
                'tax_query'           => $tax_query,
            )
        );

        if ( empty( $query->posts ) || ! is_array( $query->posts ) ) {
            return array();
        }

        $product_ids = array();

        foreach ( $query->posts as $post_id ) {
            $product = wc_get_product( absint( $post_id ) );

            if ( $product instanceof WC_Product_Variable ) {
                $product_ids[] = absint( $post_id );
            }
        }

        return $product_ids;
    }

    private function render_related_product_card( $product_id ) {
        $product = wc_get_product( $product_id );

        if ( ! $product instanceof WC_Product ) {
            return;
        }

        $price_html = $product->get_price_html();
        $image_html  = $product->get_image( 'woocommerce_thumbnail' );

        if ( ! $image_html ) {
            $image_html = wc_placeholder_img( 'woocommerce_thumbnail' );
        }
        ?>
        <a class="ccvt-related-card" href="<?php echo esc_url( get_permalink( $product_id ) ); ?>">
            <span class="ccvt-related-card-image"><?php echo wp_kses_post( $image_html ); ?></span>
            <span class="ccvt-related-card-copy">
                <span class="ccvt-related-card-title"><?php echo esc_html( $product->get_name() ); ?></span>
                <?php if ( $price_html ) : ?>
                    <span class="ccvt-related-card-price"><?php echo wp_kses_post( $price_html ); ?></span>
                <?php endif; ?>
            </span>
        </a>
        <?php
    }

    private function render_related_product_section( $heading, $product_ids, $section_class ) {
        if ( empty( $product_ids ) ) {
            return;
        }
        ?>
        <section class="ccvt-related-products <?php echo esc_attr( $section_class ); ?>">
            <div class="ccvt-related-products-head">
                <h2 class="ccvt-related-products-title"><?php echo esc_html( $heading ); ?></h2>
            </div>
            <div class="ccvt-related-products-track" aria-label="<?php echo esc_attr( $heading ); ?>">
                <?php foreach ( $product_ids as $product_id ) : ?>
                    <?php $this->render_related_product_card( $product_id ); ?>
                <?php endforeach; ?>
            </div>
        </section>
        <?php
    }

    public function render_related_product_carousels() {
        if ( ! is_product() ) {
            return;
        }

        $current_id = get_queried_object_id();
        $product    = $current_id ? wc_get_product( $current_id ) : null;

        if ( ! $product instanceof WC_Product ) {
            return;
        }

        $category_ids = wp_get_post_terms( $current_id, 'product_cat', array( 'fields' => 'ids' ) );
        $category_related_ids = $this->get_variable_product_ids( array( $current_id ), $category_ids, 'IN', 8 );

        $this->render_related_product_section( __( 'Related products', 'custom-color-variation-table' ), $category_related_ids, 'ccvt-related-products-category' );
    }

    public function enqueue_assets() {
        if ( ! is_product() ) {
            return;
        }

        global $product;

        if ( ! $product instanceof WC_Product ) {
            $product_id = get_queried_object_id();
            $product = $product_id ? wc_get_product( $product_id ) : null;
        }

        if ( ! $product instanceof WC_Product ) {
            return;
        }

        $show_assets = false;

        if ( $product instanceof WC_Product_Variable ) {
            $show_assets = true;
        }

        if ( $product instanceof WC_Product_Grouped && $this->has_grouped_color_variation_children( $product ) ) {
            $show_assets = true;
        }

        if ( ! $show_assets ) {
            return;
        }

        if ( function_exists( 'woo_variation_swatches' ) ) {
            $this->disable_variation_swatches_hooks();
        }

        $style_path = plugin_dir_path( __FILE__ ) . 'assets/css/custom-color-variation-table.css';
        $script_path = plugin_dir_path( __FILE__ ) . 'assets/js/custom-color-variation-table.js';
        $swiper_script_path = WP_PLUGIN_DIR . '/elementor/assets/lib/swiper/v8/swiper.min.js';
        $swiper_style_path  = WP_PLUGIN_DIR . '/elementor/assets/lib/swiper/v8/css/swiper.min.css';
        $style_ver   = file_exists( $style_path ) ? filemtime( $style_path ) : '1.0.0';
        $script_ver  = file_exists( $script_path ) ? filemtime( $script_path ) : '1.0.0';

        wp_enqueue_style(
            'ccvt-style',
            plugin_dir_url( __FILE__ ) . 'assets/css/custom-color-variation-table.css',
            array(),
            $style_ver
        );

        wp_enqueue_script(
            'ccvt-script',
            plugin_dir_url( __FILE__ ) . 'assets/js/custom-color-variation-table.js',
            array( 'jquery', 'wc-cart-fragments' ),
            $script_ver,
            true
        );

        if ( file_exists( $swiper_style_path ) ) {
            wp_enqueue_style(
                'ccvt-swiper',
                plugins_url( 'assets/lib/swiper/v8/css/swiper.min.css', WP_PLUGIN_DIR . '/elementor/elementor.php' ),
                array(),
                filemtime( $swiper_style_path )
            );
        }

        if ( file_exists( $swiper_script_path ) ) {
            wp_enqueue_script(
                'ccvt-swiper',
                plugins_url( 'assets/lib/swiper/v8/swiper.min.js', WP_PLUGIN_DIR . '/elementor/elementor.php' ),
                array(),
                filemtime( $swiper_script_path ),
                true
            );
        }

        wp_localize_script(
            'ccvt-script',
            'ccvt_params',
            array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce'    => wp_create_nonce( 'ccvt_add_to_cart' ),
                'strings'  => array(
                    'no_items'        => __( 'Please choose at least one quantity before adding to cart.', 'custom-color-variation-table' ),
                    'added_to_cart'   => __( 'Selected items were added to your cart.', 'custom-color-variation-table' ),
                    'update_count'    => __( 'Update cart count', 'custom-color-variation-table' ),
                    'out_of_stock'    => __( 'Out of stock', 'custom-color-variation-table' ),
                    'ajax_error'      => __( 'There was a problem adding items to the cart. Please try again.', 'custom-color-variation-table' ),
                ),
            )
        );
    }

    public function enqueue_cart_assets() {
        if ( ! is_cart() ) {
            return;
        }

        $style_path = plugin_dir_path( __FILE__ ) . 'assets/css/custom-color-variation-table.css';
        $script_path = plugin_dir_path( __FILE__ ) . 'assets/js/custom-color-variation-table-cart.js';
        $style_ver   = file_exists( $style_path ) ? filemtime( $style_path ) : '1.0.0';

        if ( ! wp_style_is( 'ccvt-style', 'enqueued' ) ) {
            wp_enqueue_style(
                'ccvt-style',
                plugin_dir_url( __FILE__ ) . 'assets/css/custom-color-variation-table.css',
                array(),
                $style_ver
            );
        }

        if ( ! file_exists( $script_path ) ) {
            return;
        }

        wp_enqueue_script(
            'ccvt-cart-script',
            plugin_dir_url( __FILE__ ) . 'assets/js/custom-color-variation-table-cart.js',
            array( 'jquery' ),
            filemtime( $script_path ),
            true
        );
    }

    private function is_color_only_variation_product( $product ) {
        $attributes = $product->get_attributes();
        $variation_attributes = array_filter( $attributes, function( $attribute ) {
            return $attribute->get_variation();
        } );

        if ( 1 !== count( $variation_attributes ) ) {
            return false;
        }

        $attribute = reset( $variation_attributes );
        $name      = $attribute->get_name();

        return false !== stripos( $name, 'color' );
    }

    private function has_grouped_color_variation_children( $product ) {
        if ( ! $product instanceof WC_Product_Grouped ) {
            return false;
        }

        foreach ( $product->get_children() as $child_id ) {
            $child = wc_get_product( $child_id );
            if ( $child instanceof WC_Product_Variable && $this->is_color_only_variation_product( $child ) ) {
                return true;
            }
        }

        return false;
    }

    public function render_grouped_child_quantity_column( $html, $grouped_product_child ) {
        if ( ! $grouped_product_child instanceof WC_Product_Variable ) {
            return $html;
        }

        if ( ! $this->is_color_only_variation_product( $grouped_product_child ) ) {
            return $html;
        }

        return $this->get_grouped_child_variation_table( $grouped_product_child );
    }

    public function render_grouped_notices() {
        return;
    }

    private function get_grouped_child_variation_table( $product ) {
        $available_variations = $product->get_available_variations();
        if ( ! is_array( $available_variations ) ) {
            $available_variations = array();
        }

        if ( empty( $available_variations ) ) {
            foreach ( $product->get_children() as $variation_id ) {
                $variation = wc_get_product( absint( $variation_id ) );
                if ( ! $variation || ! $variation->is_type( 'variation' ) ) {
                    continue;
                }

                $available_variations[] = array(
                    'variation_id' => absint( $variation_id ),
                    'attributes'   => $variation->get_variation_attributes(),
                );
            }
        }

        if ( empty( $available_variations ) ) {
            return '<span class="ccvt-empty-variations">' . esc_html__( 'No variations available.', 'custom-color-variation-table' ) . '</span>';
        }

        $quantity_step = $this->get_quantity_step_for_product( $product );

        ob_start();
        ?>
        <div class="ccvt-grouped-variation-table-wrapper">
            <table class="ccvt-variation-table ccvt-grouped-variation-table" cellspacing="0" role="presentation">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Variation', 'custom-color-variation-table' ); ?></th>
                        <th><?php esc_html_e( 'Color', 'custom-color-variation-table' ); ?></th>
                        <th><?php esc_html_e( 'Quantity', 'custom-color-variation-table' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $available_variations as $variation_data ) :
                        $variation_id = absint( $variation_data['variation_id'] );
                        $variation    = wc_get_product( $variation_id );

                        if ( ! $variation || ! $variation->is_type( 'variation' ) ) {
                            continue;
                        }

                        $raw_label = '';
                        if ( isset( $variation_data['attributes'] ) && is_array( $variation_data['attributes'] ) ) {
                            foreach ( $variation_data['attributes'] as $attribute_name => $attribute_value ) {
                                if ( false !== stripos( $attribute_name, 'color' ) ) {
                                    $raw_label = $attribute_value;
                                    break;
                                }
                            }
                        }

                        if ( ! $raw_label ) {
                            $raw_label = $variation->get_name();
                        }

                        $label = sanitize_text_field( str_replace( '-', ' ', $raw_label ) );
                        $label = ucwords( $label );
                        $image = $variation->get_image( 'thumbnail' );

                        if ( ! $image ) {
                            $image = wc_placeholder_img( 'thumbnail' );
                        }

                        $in_stock  = $variation->is_in_stock();
                        $max_qty   = $variation->get_max_purchase_quantity();
                        $stock_qty = $variation->managing_stock() ? $variation->get_stock_quantity() : 0;
                    ?>
                        <tr class="ccvt-variation-row<?php echo $in_stock ? '' : ' ccvt-out-of-stock'; ?>">
                            <td class="ccvt-variation-image"><?php echo wp_kses_post( $image ); ?></td>
                            <td class="ccvt-variation-name"><?php echo esc_html( $label ); ?>
                                <?php if ( ! $in_stock ) : ?>
                                    <div class="ccvt-stock-status"><?php esc_html_e( 'Out of stock', 'custom-color-variation-table' ); ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="ccvt-variation-quantity">
                                <div class="ccvt-qty-control">
                                    <button type="button" class="ccvt-qty-button ccvt-qty-decrement" aria-label="<?php esc_attr_e( 'Decrease quantity', 'custom-color-variation-table' ); ?>" <?php echo $in_stock ? '' : 'disabled'; ?>>-</button>
                                    <input
                                        type="number"
                                        class="ccvt-variation-qty"
                                        min="0"
                                        step="<?php echo esc_attr( $quantity_step ); ?>"
                                        value="0"
                                        data-step="<?php echo esc_attr( $quantity_step ); ?>"
                                        data-variation-id="<?php echo esc_attr( $variation_id ); ?>"
                                        data-max-qty="<?php echo esc_attr( $max_qty ); ?>"
                                        data-stock-qty="<?php echo esc_attr( $stock_qty ); ?>"
                                        <?php echo $in_stock ? '' : 'disabled'; ?>
                                    />
                                    <button type="button" class="ccvt-qty-button ccvt-qty-increment" aria-label="<?php esc_attr_e( 'Increase quantity', 'custom-color-variation-table' ); ?>" <?php echo $in_stock ? '' : 'disabled'; ?>>+</button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
        return ob_get_clean();
    }

    public function ajax_add_to_cart() {
        check_ajax_referer( 'ccvt_add_to_cart', 'security' );

        $product_id = isset( $_POST['product_id'] ) ? absint( wp_unslash( $_POST['product_id'] ) ) : 0;
        $variation_quantities = isset( $_POST['quantities'] ) ? wp_unslash( $_POST['quantities'] ) : array();
        $grouped_child_quantities = isset( $_POST['grouped_child_quantities'] ) ? wp_unslash( $_POST['grouped_child_quantities'] ) : array();

        if ( ! $product_id || ( ! is_array( $variation_quantities ) && ! is_array( $grouped_child_quantities ) ) ) {
            wp_send_json_error( array( 'message' => __( 'No valid items were submitted.', 'custom-color-variation-table' ) ) );
        }

        $product = wc_get_product( $product_id );

        if ( ! $product ) {
            wp_send_json_error( array( 'message' => __( 'Invalid product.', 'custom-color-variation-table' ) ) );
        }

        $quantity_step = $this->get_quantity_step_for_product( $product );

        $added = 0;
        $messages = array();

        if ( is_array( $grouped_child_quantities ) && $product instanceof WC_Product_Grouped ) {
            foreach ( $grouped_child_quantities as $child_id => $quantity ) {
                $child_id = absint( $child_id );
                $quantity = absint( $quantity );

                if ( $quantity <= 0 ) {
                    continue;
                }

                $child = wc_get_product( $child_id );

                if ( ! $child || ! $child->is_purchasable() || ! $child->is_in_stock() ) {
                    $messages[] = sprintf( __( 'Product %s is unavailable.', 'custom-color-variation-table' ), $child ? $child->get_name() : $child_id );
                    continue;
                }

                $quantity = $this->normalize_quantity_to_step( $quantity, $quantity_step );

                $cart_item_key = WC()->cart->add_to_cart( $child_id, $quantity );
                if ( $cart_item_key ) {
                    $added += $quantity;
                } else {
                    $messages[] = sprintf( __( 'Could not add %s to cart.', 'custom-color-variation-table' ), $child->get_name() );
                }
            }
        }

        if ( is_array( $variation_quantities ) ) {
            foreach ( $variation_quantities as $variation_id => $quantity ) {
                $variation_id = absint( $variation_id );
                $quantity     = absint( $quantity );

                if ( $quantity <= 0 ) {
                    continue;
                }

                $variation = wc_get_product( $variation_id );

                if ( ! $variation || ! $variation->is_type( 'variation' ) ) {
                    $messages[] = sprintf( __( 'Variation %s is invalid.', 'custom-color-variation-table' ), $variation_id );
                    continue;
                }

                if ( ! $variation->is_purchasable() || ! $variation->is_in_stock() ) {
                    $messages[] = sprintf( __( '%s is unavailable.', 'custom-color-variation-table' ), $variation->get_name() );
                    continue;
                }

                $quantity = $this->normalize_quantity_to_step( $quantity, $quantity_step );
                $max_purchase = $variation->get_max_purchase_quantity();
                $available_stock = $variation->managing_stock() ? $variation->get_stock_quantity() : 0;

                if ( $variation->managing_stock() && $quantity > $available_stock && ! $variation->backorders_allowed() ) {
                    $quantity = $available_stock;
                }

                if ( $max_purchase > 0 && $quantity > $max_purchase ) {
                    $quantity = $max_purchase;
                }

                if ( $quantity <= 0 ) {
                    $messages[] = sprintf( __( 'Quantity for %s is no longer available.', 'custom-color-variation-table' ), $variation->get_name() );
                    continue;
                }

                $variation_data = $variation->get_variation_attributes();
                $parent_id = $variation->get_parent_id() ? $variation->get_parent_id() : $product_id;
                $cart_item_key = WC()->cart->add_to_cart( $parent_id, $quantity, $variation_id, $variation_data );

                if ( $cart_item_key ) {
                    $added += $quantity;
                } else {
                    $messages[] = sprintf( __( 'Could not add %s to cart.', 'custom-color-variation-table' ), $variation->get_name() );
                }
            }
        }

        if ( 0 === $added ) {
            wp_send_json_error( array( 'message' => implode( ' ', $messages ) ) );
        }

        ob_start();
        woocommerce_mini_cart();
        $mini_cart = ob_get_clean();

        $fragments = apply_filters(
            'woocommerce_add_to_cart_fragments',
            array(
                'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
            )
        );

        wp_send_json_success(
            array(
                'message'    => __( 'Selected items were added to the cart.', 'custom-color-variation-table' ),
                'fragments'  => $fragments,
                'cart_hash'  => WC()->cart->get_cart_hash(),
                'quantity'   => $added,
                'notices'    => wc_print_notices( true ),
                'warnings'   => $messages,
            )
        );
    }
}

new Custom_Color_Variation_Table();
