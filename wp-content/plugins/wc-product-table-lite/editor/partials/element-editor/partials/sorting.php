<?php wcpt_how_to_use_link("https://wcproducttable.com/documentation/sorting-by-column-heading"); ?>

<!-- sorting options -->
<div class="wcpt-editor-row-option">
  <label>Sort column by:</label>
  <?php
  $orderby_options = [
    ['value' => 'title', 'label' => 'Title'],
    ['value' => 'price', 'label' => 'Price'],
    ['value' => 'menu_order', 'label' => 'Menu order'],
    ['value' => 'popularity', 'label' => 'Popularity (sales)'],
    ['value' => 'rating', 'label' => 'Rating'],
    ['value' => 'date', 'label' => 'Date of publish'],
    ['value' => 'modified', 'label' => 'Date of last modification'],
    ['value' => 'category', 'label' => 'Category'],
    ['value' => 'attribute', 'label' => 'Attribute: as text'],
    ['value' => 'attribute_num', 'label' => 'Attribute: as number'],
    ['value' => 'brand', 'label' => 'Brand'],
    ['value' => 'taxonomy', 'label' => 'Taxonomy'],
    ['value' => 'meta_value_num', 'label' => 'Custom field: as number'],
    ['value' => 'meta_value', 'label' => 'Custom field: as text'],
    ['value' => 'id', 'label' => 'Product ID'],
    ['value' => 'sku', 'label' => 'SKU: as text'],
    ['value' => 'sku_num', 'label' => 'SKU: as integer'],
  ];
  foreach ($orderby_options as $option): ?>
    <label>
      <input type="radio" name="orderby" value="<?php echo esc_attr($option['value']); ?>" wcpt-model-key="orderby">
      <?php echo esc_html($option['label']); ?>
    </label>
  <?php endforeach; ?>
</div>

<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="orderby"
  wcpt-condition-val="meta_value_num||meta_value">
  <label for="">Sort by custom field key</label>
  <input type="text" wcpt-model-key="meta_key">
</div>

<!-- orderby: category -->
<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="orderby"
  wcpt-condition-val="category">

  <?php wcpt_editor_more_options_container_start(); ?>

  <div class="wcpt-editor-row-option">
    <label>
      Ignore categories
      <small>Optional. Enter one category slug per line</small>
    </label>
    <div class="wcpt-input">
      <textarea wcpt-model-key="orderby_ignore_category"></textarea>
    </div>
  </div>

  <div class="wcpt-editor-row-option">
    <label>
      Focus categories
      <small>Optional. Enter one category slug per line</small>
    </label>
    <div class="wcpt-input">
      <textarea wcpt-model-key="orderby_focus_category"></textarea>
    </div>
  </div>

  <?php wcpt_editor_more_options_container_end(); ?>

</div>

<!-- orderby: attribute -->
<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="orderby"
  wcpt-condition-val="attribute||attribute_num">
  <div class="wcpt-editor-row-option">
    <label>Order by attribute</label>
    <div class="wcpt-input">
      <select wcpt-model-key="orderby_attribute">
        <option value="">Select an attribute here</option>
        <?php
        foreach (wc_get_attribute_taxonomies() as $attribute) {
          ?>
          <option value="pa_<?php echo $attribute->attribute_name; ?>">
            <?php echo $attribute->attribute_label; ?>
          </option>
          <?php
        }
        ?>
      </select>
    </div>
  </div>

  <?php wcpt_editor_more_options_container_start(); ?>

  <div class="wcpt-editor-row-option">
    <label>
      Ignore attribute terms
      <br>
      <small>Optional. Enter one attribute term slug per line</small>
    </label>
    <div class="wcpt-input">
      <textarea wcpt-model-key="orderby_ignore_attribute_term"></textarea>
    </div>
  </div>

  <div class="wcpt-editor-row-option">
    <label>
      Focus attribute terms
      <br>
      <small>Optional. Enter one attribute term slug per line</small>
    </label>
    <div class="wcpt-input">
      <textarea wcpt-model-key="orderby_focus_attribute_term"></textarea>
    </div>
  </div>

  <div class="wcpt-editor-row-option">
    <label>
      <input wcpt-model-key="orderby_attribute_include_all" type="checkbox" value="on" />
      Include all
      <small>Show products that don't have the attribute, after sorted products</small>
    </label>
  </div>

  <?php wcpt_editor_more_options_container_end(); ?>

</div>

<!-- orderby: taxonomy -->
<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="orderby"
  wcpt-condition-val="taxonomy">
  <div class="wcpt-editor-row-option">
    <label>Order by taxonomy</label>
    <div class="wcpt-input">
      <select wcpt-model-key="orderby_taxonomy">
        <option value="">Select a taxonomy here</option>
        <?php
        $taxonomies = get_taxonomies(
          array(
            'public' => true,
            '_builtin' => false,
            'object_type' => array('product'),
          ),
          'objects'
        );

        foreach ($taxonomies as $taxonomy) {
          if (
            in_array($taxonomy->name, array('product_cat', 'product_shipping_class')) ||
            'pa_' == substr($taxonomy->name, 0, 3)
          ) {
            continue;
          }
          ?>
          <option value="<?php echo $taxonomy->name; ?>">
            <?php echo $taxonomy->label; ?>
          </option>
          <?php
        }
        ?>
      </select>
    </div>
  </div>

  <?php wcpt_editor_more_options_container_start(); ?>

  <div class="wcpt-editor-row-option">
    <label>
      Ignore taxonomy terms
      <br>
      <small>Optional. Enter one taxonomy term slug per line</small>
    </label>
    <div class="wcpt-input">
      <textarea wcpt-model-key="orderby_ignore_taxonomy_term"></textarea>
    </div>
  </div>

  <div class="wcpt-editor-row-option">
    <label>
      Focus taxonomy terms
      <br>
      <small>Optional. Enter one taxonomy term slug per line</small>
    </label>
    <div class="wcpt-input">
      <textarea wcpt-model-key="orderby_focus_taxonomy_term"></textarea>
    </div>
  </div>

  <div class="wcpt-editor-row-option">
    <label>
      <input wcpt-model-key="orderby_taxonomy_include_all" type="checkbox" value="on" />
      Include all
      <small>Show products that don't have the taxonomy, after sorted products</small>
    </label>
  </div>

  <?php wcpt_editor_more_options_container_end(); ?>

</div>

<div class="wcpt-editor-row-option">
  <label>HTML Class</label>
  <input type="text" wcpt-model-key="html_class" />
</div>

<!-- style -->
<div class="wcpt-editor-row-option" wcpt-model-key="style">

  <div class="wcpt-editor-row-option wcpt-toggle-options wcpt-row-accordion">

    <span class="wcpt-toggle-label">
      <?php echo wcpt_icon('paint-brush'); ?>
      Style for Element
      <?php echo wcpt_icon('chevron-down'); ?>
    </span>

    <div class="wcpt-wrapper wcpt-editor-row-option" wcpt-model-key="[id]">

      <!-- font-size -->
      <div class="wcpt-editor-row-option">
        <label>Size</label>
        <input type="text" wcpt-model-key="font-size" style="margin-bottom: 0 !important;">
      </div>

      <!-- margin -->
      <div class="wcpt-editor-row-option">
        <label>Margin</label>
        <div class="wcpt-flex-option-container">
          <input type="text" wcpt-model-key="margin-top" placeholder="top">
          <input type="text" wcpt-model-key="margin-right" placeholder="right">
          <input type="text" wcpt-model-key="margin-bottom" placeholder="bottom">
          <input type="text" wcpt-model-key="margin-left" placeholder="left">
        </div>
      </div>

    </div>

    <div class="wcpt-wrapper wcpt-editor-row-option" wcpt-model-key="[id] > .wcpt-inactive">

      <!-- font-color -->
      <div class="wcpt-editor-row-option">
        <label>Color - inactive</label>
        <input type="text" wcpt-model-key="color" class="wcpt-color-picker">
      </div>

    </div>

    <div class="wcpt-wrapper wcpt-editor-row-option" wcpt-model-key="[id] > .wcpt-active">

      <!-- font-color -->
      <div class="wcpt-editor-row-option">
        <label>Color - active</label>
        <input type="text" wcpt-model-key="color" class="wcpt-color-picker">
      </div>

    </div>

  </div>

</div>