<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

// multiple global attributes only
if (!empty($number_of_attributes) && $number_of_attributes === "multiple") {

	$attributes = !empty($attributes) ? $attributes : [];

	if (empty($property_list_options)) {
		return;
	}

	$property_list_options['rows'] = [];

	if (!isset($attribute_criteria)) {
		$attribute_criteria = "all";
	}

	if ($attribute_criteria === "all" && isset($product) && is_object($product)) {

		$product_attrs = $product->get_attributes();
		$existing_names = array_column($attributes, 'attribute_name');

		if (!empty($max_attributes)) {
			$max_attributes = intval($max_attributes);
			$product_attrs = array_slice($product_attrs, 0, $max_attributes);
		}

		// variations
		if ($product->get_type() === 'variation') {
			foreach ($product_attrs as $attr_name => $term_slug) {
				$_attr_name = preg_replace('/^pa_/', '', $attr_name);
				if (!in_array($_attr_name, $existing_names, true)) {
					$attributes[] = [
						'attribute_name' => $_attr_name,
						'enable_property_label' => true,
						'property_label_icon_source' => 'included',
						'property_label_text' => '[attribute_name]'
					];
					$existing_names[] = $_attr_name;
				}
			}

			// regular product types (not variations)
		} else {

			foreach ($product_attrs as $attr_name => $attr_obj) {
				if (
					is_a($attr_obj, 'WC_Product_Attribute') &&
					$attr_obj->is_taxonomy()
				) {
					// If $exclude_variation_attributes is true, skip attributes used for variations
					if (
						!empty($exclude_variation_attributes) &&
						method_exists($attr_obj, 'get_variation') &&
						$attr_obj->get_variation()
					) {
						continue;
					}
					$_attr_name = preg_replace('/^pa_/', '', $attr_name);
					if (!in_array($_attr_name, $existing_names, true)) {
						$attributes[] = [
							'attribute_name' => $_attr_name,
							'enable_property_label' => true,
							'property_label_icon_source' => 'included',
							'property_label_text' => '[attribute_name]'
						];
						$existing_names[] = $_attr_name;
					}
				}
			}
		}

		// exclude attributes by slug
		if (!empty($exclude_attributes)) {
			$exclude_attributes = preg_split('/\r\n|\r|\n/', $exclude_attributes);
			$attributes = array_filter($attributes, function ($item) use ($exclude_attributes) {
				return !in_array($item['attribute_name'], $exclude_attributes);
			});
		}

		// filter out attributes not present in the product, and make sure only global (taxonomy) attributes
		$attributes = array_filter($attributes, function ($item) use ($product, $product_attrs) {
			if (!isset($item['attribute_name'])) {
				return false;
			}
			$attribute_name = $item['attribute_name'];

			// variations
			if ($product->get_type() === 'variation') {
				return array_key_exists('pa_' . $attribute_name, $product_attrs);
			} else {
				return array_key_exists('pa_' . $attribute_name, $product_attrs)
					&& is_a($product_attrs['pa_' . $attribute_name], 'WC_Product_Attribute')
					&& $product_attrs['pa_' . $attribute_name]->is_taxonomy();
			}
		});
		// reset array keys
		$attributes = array_values($attributes);
	}

	foreach ($attributes as $item) {
		$inner_attribute = array_merge($element, $item);
		$inner_attribute['number_of_attributes'] = "single";
		$inner_attribute['attribute_name'] = $item['attribute_name'] ?? "";
		$inner_attribute['attribute_type'] = 'global';
		$inner_attribute['property_list_options'] = [];
		// $inner_attribute['separator'] = ', ';
		$inner_attribute['style'] = [];
		$inner_attribute['condition'] = [];

		$original_attribute_name = wc_attribute_label('pa_' . $inner_attribute['attribute_name']);

		if (empty($inner_attribute['property_label_text'])) {
			$inner_attribute['property_label_text'] = "";
		}

		$property_label_html = str_replace(
			'[attribute_name]',
			$original_attribute_name,
			wcpt_property_label($inner_attribute)
		);

		$inner_attribute['enable_property_label'] = false;

		$condition = isset($empty_relabel) ? [] : [
			"action" => "show",
			"attribute" => $inner_attribute['attribute_name'],
			"attribute_enabled" => true,
		];

		$property_list_options['rows'][] = [
			'property_name' => [
				[
					'style' => [],
					'elements' => [
						[
							'type' => 'raw',
							'content' => $property_label_html,
							"id" => "",
						]
					],
					'type' => 'row',
					'id' => "",
				]
			],
			'property_value' => [
				[
					'style' => [],
					'elements' => [
						$inner_attribute
					],
					'type' => 'row',
					'id' => "",
				]
			],
			'condition' => $condition,
			'style' => [],
		];
	}

	wcpt_new_ids($property_list_options);

	extract($property_list_options);

	$html_class .= ' wcpt-' . $id . ' ';

	include 'property_list.php';

	wcpt_parse_style_2(array_merge(array('type' => 'multi_property_grid', 'id' => $id), $property_list_options));

	return;
}

// single attribute

// no attribute selected
if (
	empty($attribute_type) ||
	(
		$attribute_type == "global" &&
		empty($attribute_name)
	) ||
	(
		$attribute_type == "custom" &&
		empty($custom_attribute_name)
	)
) {
	return;
}

// Property label (text + optional icon)
$property_label_html = '';
include 'property_label.php';

if ($attribute_type == "custom") {

	if (empty($custom_attribute_name)) {
		return;
	}

	$result_found = false;

	if ( // product variation
		$product->get_type() == 'variation' &&
		$term = get_post_meta($product->get_id(), 'attribute_' . $custom_attribute_name, true)
	) {

		$result_found = true;

		?>
		<div class="wcpt-attribute wcpt-custom-attribute <?php echo $html_class; ?>">
			<?php echo $property_label_html; ?>
			<div class="wcpt-attribute-term ">
				<?php echo esc_html($term); ?>
			</div>
		</div>
		<?php

	} else { // other product types
		$attributes = $product->get_attributes();

		if ($val = $product->get_attribute(trim($custom_attribute_name))) {

			if (empty($separator)) {
				$separator = '';
			} else {
				$separator = wcpt_parse_2($separator);
			}

			$custom_terms = array_map('trim', explode('|', $val));
			$result_found = true;

			?>
			<div class="wcpt-attribute wcpt-custom-attribute <?php echo $html_class; ?>">
				<?php echo $property_label_html; ?>
				<?php foreach ($custom_terms as $index => $custom_term): ?>
					<div class="wcpt-attribute-term"><?php echo esc_html($custom_term); ?></div>
					<?php if ($index < count($custom_terms) - 1): ?>
						<div class="wcpt-attribute-term-separator wcpt-term-separator"><?php echo $separator ?></div>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
			<?php
		}

	}

	if (!$result_found) {
		if (empty($empty_relabel)) {
			$empty_relabel = '';
		}

		echo wcpt_parse_2($empty_relabel);
	}

	return;
}

$taxonomy = 'pa_' . $attribute_name;

// product variation
if (in_array($product->get_type(), array('subscription_variation', 'variation'))) {
	$field_name = 'attribute_pa_' . strtolower(urlencode($attribute_name));
	$field_value = get_post_meta($product->get_id(), $field_name, true);

	// no term on variation
	if (!$field_value) {
		if (!$parent = wc_get_product($product->get_parent_id())) {
			return;
		}

		$variation_attributes = $parent->get_variation_attributes();

		if (empty($variation_attributes['pa_' . $attribute_name])) {
			// term is on parent
			$parent_attributes = $parent->get_attributes();
			if (!empty($parent_attributes['pa_' . $attribute_name])) {
				$product = $parent;
				include 'attribute.php';
			}

			return;
		}

		// selectable term
		$term_slugs = $variation_attributes['pa_' . $attribute_name];

		?>
		<select class="wcpt-select-variation-attribute-term"
			data-wcpt-attribute="<?php echo 'attribute_pa_' . strtolower(urlencode($attribute_name)); ?>">
			<option value=""><?php echo esc_attr(wc_attribute_label('pa_' . $attribute_name)); ?></option>
			<?php
			foreach ($term_slugs as $slug) {
				$term = get_term_by('slug', $slug, 'pa_' . $attribute_name);
				echo '<option value="' . esc_attr(urldecode($slug)) . '">' . urldecode($term->name) . '</option>';
			}
			?>
		</select>
		<?php
		return;
	} else {
		$terms = array(get_term_by('slug', $field_value, 'pa_' . $attribute_name));

	}

} else { // other product types (non-variation)

	$attributes = $product->get_attributes();

	$attribute_name__urlencode = strtolower(urlencode($attribute_name));

	if (isset($attributes[$attribute_name__urlencode])) {
		$attribute_object = $attributes[$attribute_name__urlencode];

	} elseif (isset($attributes['pa_' . $attribute_name__urlencode])) {
		$attribute_object = $attributes['pa_' . $attribute_name__urlencode];

	}

	if (empty($attribute_object)) {
		$terms = false;

	} else if ($attribute_object && $attribute_object->is_taxonomy()) {
		$terms = wc_get_product_terms($product->get_id(), $attribute_object->get_name(), array('fields' => 'all', 'orderby' => 'menu_order'));

	} else { // text attribute
		$terms = $attribute_object->get_options();

	}

}

// excludes array
$excludes_arr = array();
if (!empty($exclude_terms)) {
	$excludes_arr = preg_split('/\r\n|\r|\n/', $exclude_terms);
}

if ($terms && count($terms)) {
	// associated terms exist

	if (empty($separator)) {
		$separator = '';
	} else {
		$separator = wcpt_parse_2($separator);
	}

	$output = '';

	if (empty($relabels)) {
		$relabels = array();
	}

	// sort terms prioritizing current fitler
	global $wcpt_table_data;
	$table_id = $wcpt_table_data['id'];
	$filter_key = $table_id . '_attr_pa_' . $attribute_name;
	if (!empty($_GET[$filter_key]) && !empty($terms)) {
		$_terms = array();
		foreach ($terms as $term) {
			$_terms[$term->term_id] = $term;
		}
		$safe_filter_keys = is_array($_GET[$filter_key]) ? array_map('sanitize_text_field', $_GET[$filter_key]) : sanitize_text_field($_GET[$filter_key]);
		$terms = array_replace(array_intersect_key(array_flip($safe_filter_keys), $_terms), $_terms);
	}

	$terms = array_values($terms);

	// relabel each term
	foreach ($terms as $index => $term) {

		if (gettype($term) !== 'object') {
			continue;
		}

		// exclude
		if (
			in_array($term->name, $excludes_arr) ||
			in_array($term->slug, $excludes_arr)
		) {
			continue;
		}

		// filtering
		if (
			!empty($_GET[$filter_key]) &&
			is_array($_GET[$filter_key]) &&
			in_array($term->term_taxonomy_id, $_GET[$filter_key])
		) {
			$filtering = 'true';
		} else {
			$filtering = 'false';
		}

		// is link
		$archive_url = get_term_link($term->term_id, $taxonomy);
		if (is_wp_error($archive_url)) {
			$archive_url = '';
		}

		if (
			!empty($click_action) &&
			$click_action == 'archive_redirect' &&
			$archive_url
		) {
			$is_link = true;
		} else {
			$is_link = false;
		}

		// data attrs
		$common_data_attrs = 'data-wcpt-slug="' . $term->slug . '" data-wcpt-filtering="' . $filtering . '" data-wcpt-archive-url="' . $archive_url . '"';
		if ($is_link) {
			$common_data_attrs .= ' href="' . $archive_url . '" ';
		}

		// look for a matching rule
		$match = false;

		foreach ($relabels as $rule) {
			// skip default
			if (wcpt_is_default_relabel($rule)) {
				continue;
			}

			if (
				wp_specialchars_decode($term->name) == $rule['term'] ||
				(
					function_exists('icl_object_id') &&
					!empty($rule['ttid']) &&
					$term->term_taxonomy_id == icl_object_id($rule['ttid'], 'pa_' . $attribute_name, false)
				)
			) {
				$match = true;

				// style
				wcpt_parse_style_2($rule, '!important');
				$term_html_class = 'wcpt-' . $rule['id'];

				// append
				$label = str_replace('[term]', $term->name, wcpt_parse_2($rule['label']));

				// wrap in a / div tag
				if ($is_link) {
					$output .= '<a class="wcpt-attribute wcpt-attribute-term ' . $term_html_class . '" ' . $common_data_attrs . '>' . $label . '</a>';
				} else {
					$output .= '<div class="wcpt-attribute wcpt-attribute-term ' . $term_html_class . '" ' . $common_data_attrs . '>' . $label . '</div>';
				}

				break;
			}
		}

		if (!$match) {
			$term_name = apply_filters('wcpt_term_name_in_column', $term->name, $term);

			if ($is_link) {
				$output .= '<a class="wcpt-attribute wcpt-attribute-term" ' . $common_data_attrs . '>' . $term_name . '</a>';
			} else {
				$output .= '<div class="wcpt-attribute wcpt-attribute-term" ' . $common_data_attrs . '>' . $term_name . '</div>';
			}
		}

		if ($index < count($terms) - 1) {
			$output .= '<div class="wcpt-attribute-term-separator wcpt-term-separator">' . $separator . '</div>';
		}

	}

} else {
	// no associated terms

	if (empty($empty_relabel)) {
		$empty_relabel = '';
	}

	$output = wcpt_parse_2($empty_relabel);

}

if (empty($click_action)) {
	$click_action = false;
}

if (
	$click_action == 'trigger_filter' &&
	!wcpt_check_if_nav_has_filter(null, 'attribute_filter', $attribute_name)
) {
	$click_action = false;
}

if ($click_action) {
	$html_class .= ' wcpt-' . $click_action . ' ';
}

if (!empty($separate_lines)) {
	$html_class .= ' wcpt-terms-in-separate-lines ';
}

if (!empty($output)) {
	echo '<div class="wcpt-attributes ' . $html_class . '" data-wcpt-taxonomy="' . $taxonomy . '" data-wcpt-term-count="' . ($terms ? count($terms) : '0') . '">' . $property_label_html . $output . '</div>';
}
