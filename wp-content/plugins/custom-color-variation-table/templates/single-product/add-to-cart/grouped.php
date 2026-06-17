<?php
/**
 * Custom grouped product add to cart template.
 *
 * @see https://woocommerce.com/document/template-structure/
 */

defined( 'ABSPATH' ) || exit;

global $product, $post;

$quantities_required = false;
$previous_post       = $post;
$show_add_to_cart    = false;
$items               = array();
$total_min_price     = 0;
$total_max_price     = 0;
$quantity_step       = absint( get_post_meta( $product->get_id(), '_ccvt_quantity_step', true ) );

if ( ! in_array( $quantity_step, array( 1, 6 ), true ) ) {
	$quantity_step = 1;
}

foreach ( $grouped_products as $grouped_product_child ) {
	$post_object         = get_post( $grouped_product_child->get_id() );
	$quantities_required = $quantities_required || ( $grouped_product_child->is_purchasable() && ! $grouped_product_child->has_options() );
	$post                = $post_object; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	setup_postdata( $post );

	if ( $grouped_product_child->is_in_stock() ) {
		$show_add_to_cart = true;
	}

	$price      = (float) wc_get_price_to_display( $grouped_product_child );
	$min_price  = $price;
	$max_price  = $price;
	$image_html = $grouped_product_child->get_image( 'thumbnail' );

	if ( ! $image_html ) {
		$image_html = wc_placeholder_img( 'thumbnail' );
	}

	$label = $grouped_product_child->get_name();

	$attributes = $grouped_product_child->get_attributes();
	if ( is_array( $attributes ) ) {
		foreach ( $attributes as $attribute ) {
			if ( ! $attribute instanceof WC_Product_Attribute ) {
				continue;
			}

			$name = $attribute->get_name();
			if ( false === stripos( $name, 'color' ) ) {
				continue;
			}

			if ( $attribute->is_taxonomy() ) {
				$terms = wc_get_product_terms( $grouped_product_child->get_id(), $name, array( 'fields' => 'names' ) );
				if ( ! empty( $terms ) ) {
					$label = implode( ', ', array_map( 'wc_clean', $terms ) );
					break;
				}
			} else {
				$options = $attribute->get_options();
				if ( ! empty( $options ) ) {
					$label = implode( ', ', array_map( 'wc_clean', $options ) );
					break;
				}
			}
		}
	}

	$items[] = array(
		'id'             => $grouped_product_child->get_id(),
		'label'          => $label,
		'name'           => $grouped_product_child->get_name(),
		'image'          => $image_html,
		'price_html'     => $grouped_product_child->get_price_html(),
		'price'          => $price,
		'min_price'      => $min_price,
		'max_price'      => $max_price,
		'max_qty'        => $grouped_product_child->get_max_purchase_quantity(),
		'in_stock'       => $grouped_product_child->is_in_stock(),
		'is_purchasable' => $grouped_product_child->is_purchasable(),
		'has_options'    => $grouped_product_child->has_options(),
	);

	$total_min_price += $min_price;
	$total_max_price += $max_price;
}

$post = $previous_post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
setup_postdata( $post );

$range_html = wc_price( $total_min_price );
if ( $total_max_price > $total_min_price ) {
	$range_html = wc_price( $total_min_price ) . ' - ' . wc_price( $total_max_price );
}

do_action( 'woocommerce_before_add_to_cart_form' );
?>

<form class="cart grouped_form ccvt-grouped-form" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
	<div class="ccvt-shell ccvt-grouped-shell">
		<div class="ccvt-grouped-heading">
			<div class="ccvt-grouped-range"><?php echo wp_kses_post( $range_html ); ?></div>
		</div>

		<div class="ccvt-notices" aria-live="polite"></div>

		<div class="ccvt-table-wrap">
			<table cellspacing="0" class="ccvt-grouped-table woocommerce-grouped-product-list group_table" role="presentation">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Color', 'custom-color-variation-table' ); ?></th>
						<th><?php esc_html_e( 'Price', 'custom-color-variation-table' ); ?></th>
						<th><?php esc_html_e( 'Qty', 'custom-color-variation-table' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $items as $item ) : ?>
						<tr class="ccvt-grouped-row<?php echo $item['in_stock'] ? '' : ' ccvt-out-of-stock'; ?>">
							<td class="ccvt-grouped-cell ccvt-grouped-product">
								<div class="ccvt-grouped-product-card">
									<div class="ccvt-grouped-product-image"><?php echo wp_kses_post( $item['image'] ); ?></div>
									<div class="ccvt-grouped-product-copy">
										<span class="ccvt-grouped-product-label"><?php echo esc_html( $item['label'] ); ?></span>
										<?php if ( ! $item['in_stock'] ) : ?>
											<span class="ccvt-stock-status"><?php esc_html_e( 'Out of stock', 'custom-color-variation-table' ); ?></span>
										<?php endif; ?>
									</div>
								</div>
							</td>
							<td class="ccvt-grouped-cell ccvt-grouped-price">
								<span class="ccvt-grouped-price-value"><?php echo wp_kses_post( $item['price_html'] ); ?></span>
							</td>
							<td class="ccvt-grouped-cell ccvt-grouped-quantity">
								<?php if ( $item['is_purchasable'] && ! $item['has_options'] && $item['in_stock'] ) : ?>
									<div class="ccvt-qty-control ccvt-grouped-qty-control">
										<button type="button" class="ccvt-qty-button ccvt-grouped-qty-button ccvt-grouped-qty-decrement" aria-label="<?php esc_attr_e( 'Decrease quantity', 'custom-color-variation-table' ); ?>">-</button>
										<input
											type="number"
											class="ccvt-grouped-qty-input"
											name="<?php echo esc_attr( 'quantity[' . $item['id'] . ']' ); ?>"
											min="0"
											step="<?php echo esc_attr( $quantity_step ); ?>"
											value="0"
											placeholder="0"
											data-step="<?php echo esc_attr( $quantity_step ); ?>"
											data-product-id="<?php echo esc_attr( $item['id'] ); ?>"
											data-price="<?php echo esc_attr( wc_format_decimal( $item['price'], wc_get_price_decimals() ) ); ?>"
											data-max-qty="<?php echo esc_attr( $item['max_qty'] ); ?>"
											aria-label="<?php echo esc_attr( sprintf( __( 'Quantity for %s', 'custom-color-variation-table' ), $item['name'] ) ); ?>"
										/>
										<button type="button" class="ccvt-qty-button ccvt-grouped-qty-button ccvt-grouped-qty-increment" aria-label="<?php esc_attr_e( 'Increase quantity', 'custom-color-variation-table' ); ?>">+</button>
									</div>
								<?php else : ?>
									<span class="ccvt-grouped-unavailable"><?php esc_html_e( 'Unavailable', 'custom-color-variation-table' ); ?></span>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>

		<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" />

		<?php if ( $quantities_required && $show_add_to_cart ) : ?>
			<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

			<div class="ccvt-footer ccvt-grouped-footer">
				<div class="ccvt-summary ccvt-grouped-summary" aria-live="polite">
					<span class="ccvt-summary-label"><?php esc_html_e( 'Selected items', 'custom-color-variation-table' ); ?></span>
					<strong class="ccvt-total-items">0</strong>
					<span class="ccvt-summary-separator">-</span>
					<strong class="ccvt-total-price" data-currency-symbol="<?php echo esc_attr( get_woocommerce_currency_symbol() ); ?>"><?php echo wp_kses_post( wc_price( 0 ) ); ?></strong>
				</div>
				<button type="submit" class="single_add_to_cart_button button alt ccvt-add-to-cart" disabled>
					<?php esc_html_e( 'Add to cart', 'custom-color-variation-table' ); ?>
					<span class="ccvt-button-count">(0)</span>
				</button>
			</div>

			<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
		<?php endif; ?>
	</div>
</form>

<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
