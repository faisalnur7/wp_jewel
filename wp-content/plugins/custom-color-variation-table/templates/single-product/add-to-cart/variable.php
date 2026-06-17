<?php
/**
 * Custom variable product add to cart template for Color variations.
 *
 * @see https://woocommerce.com/document/template-structure/
 */

defined( 'ABSPATH' ) || exit;

global $product, $available_variations, $attributes;

if ( ! $product instanceof WC_Product_Variable ) {
    return;
}

if ( ! is_array( $attributes ) ) {
    $attributes = $product->get_variation_attributes();
}

if ( ! is_array( $available_variations ) ) {
    $available_variations = $product instanceof WC_Product_Variable ? $product->get_available_variations() : array();
}

$variation_attribute_name = '';
if ( is_array( $attributes ) ) {
    foreach ( $attributes as $name => $options ) {
        if ( false !== stripos( $name, 'color' ) ) {
            $variation_attribute_name = $name;
            break;
        }
    }
}

if ( ! $variation_attribute_name ) {
    $product_attributes = $product->get_attributes();
    foreach ( $product_attributes as $name => $attribute ) {
        if ( $attribute instanceof WC_Product_Attribute && $attribute->get_variation() && false !== stripos( $name, 'color' ) ) {
            $variation_attribute_name = $name;
            break;
        }
    }
}

if ( ! $variation_attribute_name ) {
    include WC()->plugin_path() . '/templates/single-product/add-to-cart/variable.php';
    return;
}

$quantity_step = absint( get_post_meta( $product->get_id(), '_ccvt_quantity_step', true ) );
if ( ! in_array( $quantity_step, array( 1, 6 ), true ) ) {
    $quantity_step = 1;
}

$variation_ids = array();
if ( is_array( $available_variations ) && ! empty( $available_variations ) ) {
    foreach ( $available_variations as $variation_data ) {
        if ( empty( $variation_data['variation_id'] ) ) {
            continue;
        }

        $variation_ids[] = absint( $variation_data['variation_id'] );
    }
}

if ( empty( $variation_ids ) && $product instanceof WC_Product_Variable ) {
    $variation_ids = $product->get_children();
}

$variation_ids = array_unique( array_filter( $variation_ids, 'absint' ) );

$variations = array();

foreach ( $variation_ids as $variation_id ) {
    $variation = wc_get_product( absint( $variation_id ) );

    if ( ! $variation || ! $variation->is_type( 'variation' ) ) {
        continue;
    }

    $variation_attributes = $variation->get_variation_attributes();
    $raw_label = '';

    if ( isset( $variation_attributes[ $variation_attribute_name ] ) ) {
        $raw_label = $variation_attributes[ $variation_attribute_name ];
    } elseif ( isset( $variation_attributes[ str_replace( 'attribute_', '', $variation_attribute_name ) ] ) ) {
        $raw_label = $variation_attributes[ str_replace( 'attribute_', '', $variation_attribute_name ) ];
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

	$in_stock = $variation->is_in_stock();
	$max_qty = $variation->get_max_purchase_quantity();
	$stock_qty = $variation->managing_stock() ? $variation->get_stock_quantity() : 0;
	$price_html = $variation->get_price_html();

	$variations[] = array(
		'id'         => $variation_id,
		'label'      => $label,
		'image'      => $image,
		'price_html' => $price_html,
		'in_stock'   => $in_stock,
		'max_qty'    => $max_qty,
		'stock_qty'  => $stock_qty,
		'permalink'  => $variation->get_permalink(),
        'variation'  => $variation,
    );
}
?>

<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<form class="variations_form cart ccvt-form" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>" data-product_variations="<?php echo wc_esc_json( wp_json_encode( $available_variations ) ); ?>">
    <?php do_action( 'woocommerce_before_variations_form' ); ?>

    <div class="ccvt-shell">
        <div class="ccvt-notices" aria-live="polite"></div>

        <select name="variation_id" style="display: none;"><option value=""></option></select>

        <div class="ccvt-table-wrap">
            <table class="ccvt-variation-table" cellspacing="0" role="presentation">
                <thead>
                    <tr>
                        <th class="color-th"><?php esc_html_e( 'Color', 'custom-color-variation-table' ); ?></th>
                        <th class="price-th"><?php esc_html_e( 'Price', 'custom-color-variation-table' ); ?></th>
                        <th class="quantity-th"><?php esc_html_e( 'Quantity', 'custom-color-variation-table' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $variations as $data ) : ?>
                        <tr class="ccvt-variation-row<?php echo $data['in_stock'] ? '' : ' ccvt-out-of-stock'; ?>">
                            <td class="ccvt-variation-cell ccvt-variation-meta">
                                <span class="ccvt-variation-image">
                                    <?php echo wp_kses_post( $data['image'] ); ?>
                                </span>
                                <span class="ccvt-variation-copy">
                                    <span class="ccvt-variation-label"><?php echo esc_html( $data['label'] ); ?></span>
                                    <?php if ( ! $data['in_stock'] ) : ?>
                                        <span class="ccvt-stock-status"><?php esc_html_e( 'Out of stock', 'custom-color-variation-table' ); ?></span>
                                    <?php endif; ?>
                                </span>
                            </td>
                            <td class="ccvt-variation-cell ccvt-variation-price">
                                <span class="ccvt-variation-price-value"><?php echo wp_kses_post( $data['price_html'] ); ?></span>
                            </td>
                            <td class="ccvt-variation-cell ccvt-variation-quantity">
                                <div class="ccvt-qty-control">
                                    <button type="button" class="ccvt-qty-button ccvt-qty-decrement" aria-label="<?php esc_attr_e( 'Decrease quantity', 'custom-color-variation-table' ); ?>" <?php echo $data['in_stock'] ? '' : 'disabled'; ?>>-</button>
                                    <input
                                        type="number"
                                        class="ccvt-variation-qty"
                                        min="0"
                                        step="<?php echo esc_attr( $quantity_step ); ?>"
                                        value="0"
                                        data-step="<?php echo esc_attr( $quantity_step ); ?>"
                                        data-variation-id="<?php echo esc_attr( $data['id'] ); ?>"
                                        data-max-qty="<?php echo esc_attr( $data['max_qty'] ); ?>"
                                        data-stock-qty="<?php echo esc_attr( $data['stock_qty'] ); ?>"
                                        <?php echo $data['in_stock'] ? '' : 'disabled'; ?>
                                        aria-label="<?php echo esc_attr( sprintf( __( 'Quantity for %s', 'custom-color-variation-table' ), $data['label'] ) ); ?>"
                                    />
                                    <button type="button" class="ccvt-qty-button ccvt-qty-increment" aria-label="<?php esc_attr_e( 'Increase quantity', 'custom-color-variation-table' ); ?>" <?php echo $data['in_stock'] ? '' : 'disabled'; ?>>+</button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="ccvt-footer">
            <div class="ccvt-summary" aria-live="polite">
                <span class="ccvt-summary-label"><?php esc_html_e( 'Selected items', 'custom-color-variation-table' ); ?></span>
                <strong class="ccvt-total-items">0</strong>
            </div>
            <button type="submit" class="single_add_to_cart_button button alt ccvt-add-to-cart" disabled>
                <?php esc_html_e( 'Add to cart', 'custom-color-variation-table' ); ?>
                <span class="ccvt-button-count">(0)</span>
            </button>
        </div>
    </div>

    <?php wp_nonce_field( 'ccvt_add_to_cart', 'ccvt_add_to_cart_nonce' ); ?>
    <input type="hidden" name="product_id" value="<?php echo absint( $product->get_id() ); ?>" />

    <?php do_action( 'woocommerce_after_variations_form' ); ?>
</form>

<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
