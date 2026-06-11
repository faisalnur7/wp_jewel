<?php
/**
 * Plugin Name: Custom Color Variation Table
 * Description: Replaces WooCommerce variable product color dropdown with a bulk quantity table on the product page.
 * Version: 1.0.0
 * Author: Custom WooCommerce Implementation
 * Text Domain: custom-color-variation-table
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Custom_Color_Variation_Table {
    public function __construct() {
        add_action( 'plugins_loaded', array( $this, 'init' ), 20 );
    }

    public function init() {
        add_filter( 'woocommerce_locate_template', array( $this, 'locate_template' ), 10, 3 );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
        add_action( 'wp_ajax_ccvt_add_to_cart', array( $this, 'ajax_add_to_cart' ) );
        add_action( 'wp_ajax_nopriv_ccvt_add_to_cart', array( $this, 'ajax_add_to_cart' ) );
        add_filter( 'woocommerce_grouped_product_list_column_quantity', array( $this, 'render_grouped_child_quantity_column' ), 10, 2 );
        add_action( 'woocommerce_before_add_to_cart_form', array( $this, 'render_grouped_notices' ) );
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
        if ( in_array( $template_name, array( 'single-product/add-to-cart/variable.php', 'single-product/add-to-cart/grouped.php' ), true ) ) {
            $plugin_template = plugin_dir_path( __FILE__ ) . 'templates/' . $template_name;

            if ( file_exists( $plugin_template ) ) {
                $template = $plugin_template;
            }
        }

        return $template;
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
                                        step="1"
                                        value="0"
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
