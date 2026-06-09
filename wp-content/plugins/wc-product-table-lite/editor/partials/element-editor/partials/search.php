<?php wcpt_how_to_use_link("https://wcproducttable.com/documentation/search"); ?>

<!-- heading -->
<div class="wcpt-editor-row-option">
  <label>
    Heading
    <small>Leave empty for no heading</small>
  </label>
  <div wcpt-block-editor wcpt-model-key="heading"></div>
</div>

<!-- heading in separate line -->
<div class="wcpt-editor-row-option">
  <label>
    <input type="checkbox" wcpt-model-key="heading_separate_line">
    Show heading in a separate line above
  </label>
</div>

<!-- placeholder -->
<div class="wcpt-editor-row-option">
  <label>Placeholder</label>
  <input type="text" wcpt-model-key="placeholder">
</div>

<!-- connect with archive search -->
<div class="wcpt-editor-row-option">
  <label>
    <input type="checkbox" wcpt-model-key="connect_with_archive_search">
    Connect with archive search
    <small>Redirect visitor to the product search page</small>
  </label>
</div>

<div wcpt-panel-condition="prop" wcpt-condition-prop="connect_with_archive_search" wcpt-condition-val="false">
  <!-- keyword match type -->
  <div class="wcpt-editor-row-option">
    <label>Keyword match type</label>
    <select wcpt-model-key="keyword_match_type">
      <option value="any">Match any keyword</option>
      <option value="all">Match all keywords</option>
    </select>
  </div>

  <!-- override global settings -->
  <div class="wcpt-editor-row-option">
    <label>Select target fields to search through:</label>
    <!-- target -->
    <?php foreach (array('Title', 'Content', 'Excerpt', 'SKU', 'Custom field', 'Category', 'Attribute', 'Brand', 'GTIN', 'Tag') as $field): ?>
      <?php $model_val = strtolower(str_replace(' ', '_', $field)); ?>
      <?php
      if (in_array($field, array('Title', 'Content', 'Excerpt'))) {
        ?>
        <label>
          <input type="checkbox" value="<?php echo $model_val; ?>" wcpt-model-key="target[]" />
          <?php
          if ($field == 'Excerpt') {
            echo 'Short description';
          } else {
            echo $field;
          }
          ?>
        </label>
        <?php
      } else {
        wcpt_pro_checkbox($model_val, $field, "target[]");
      }
      ?>

      <?php if ($model_val === 'custom_field'): ?>
        <div class="wcpt-checkbox-selection-group" wcpt-panel-condition="prop" wcpt-condition-prop="target"
          wcpt-condition-val="custom_field">
          <label>Select custom fields to search through:</label>
          <?php foreach (wcpt_get_product_custom_fields() as $meta_name): ?>
            <label class="wcpt-editor-checkbox-label">
              <input type="checkbox" wcpt-controller="checkbox_selection" wcpt-model-key="custom_fields[]"
                value="<?php echo esc_attr($meta_name); ?>" />
              <?php echo esc_attr($meta_name); ?>
            </label>
          <?php endforeach; ?>

          <!-- enter key -->
          <div class="wcpt-editor-row-option" style="margin-bottom: 20px; padding-left: 0;">
            <label>You can also enter custom field names one per line:</label>
            <textarea wcpt-model-key="custom_fields_textarea"></textarea>
          </div>

        </div>

      <?php endif; ?>

      <?php if ($model_val === 'attribute'): ?>
        <div class="wcpt-checkbox-selection-group" wcpt-panel-condition="prop" wcpt-condition-prop="target"
          wcpt-condition-val="attribute">
          <label>Select attributes to search through:</label>
          <?php foreach (wc_get_attribute_taxonomies() as $attribute): ?>
            <label class="wcpt-editor-checkbox-label">
              <input type="checkbox" wcpt-controller="checkbox_selection" wcpt-model-key="attributes[]"
                value="<?php echo esc_attr($attribute->attribute_name); ?>" />
              <?php echo esc_attr($attribute->attribute_label); ?>
            </label>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

    <?php endforeach; ?>
  </div>

  <!-- include variation skus -->
  <div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="target" wcpt-condition-val="sku">
    <label>
      <input type="checkbox" wcpt-model-key="include_variation_skus" />
      Include variation SKUs in search
    </label>
  </div>

  <!-- include variation gtin -->
  <div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="target"
    wcpt-condition-val="gtin">
    <label>
      <input type="checkbox" wcpt-model-key="include_variation_gtins" />
      Include variation GTINs in search
    </label>
  </div>

  <!-- reset others -->
  <div class="wcpt-editor-row-option">
    <label>
      <input type="checkbox" wcpt-model-key="reset_others" />
      Reset other navigation filters during search
    </label>
  </div>

  <!-- clear label -->
  <div class="wcpt-editor-row-option">
    <label>
      Text in 'clear search' option
      <small>use [kw] as placeholder for the search keyword</small>
    </label>
    <input type="text" wcpt-model-key="clear_label" placeholder="Search: [kw]">
  </div>

</div>

<!-- html class -->
<div class="wcpt-editor-row-option">
  <label>HTML Class</label>
  <input type="text" wcpt-model-key="html_class" />
</div>

<div class="wcpt-editor-row-option" wcpt-model-key="style">

  <div class="wcpt-editor-row-option wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="[id]">

    <span class="wcpt-toggle-label">
      <?php echo wcpt_icon('paint-brush'); ?>
      Style for Search
      <?php echo wcpt_icon('chevron-down'); ?>
    </span>

    <!-- font-size -->
    <div class="wcpt-editor-row-option">
      <label>Font size</label>
      <input type="text" wcpt-model-key="font-size" placeholder="16px" wcpt-initial-data="">
    </div>

    <!-- font-color -->
    <div class="wcpt-editor-row-option">
      <label>Font color</label>
      <input type="text" wcpt-model-key="color" placeholder="#000" class="wcpt-color-picker">
    </div>

    <!-- width -->
    <div class="wcpt-editor-row-option">
      <label>Width</label>
      <input type="text" wcpt-model-key="width" />
    </div>

    <!-- width -->
    <div class="wcpt-editor-row-option">
      <label>Height</label>
      <input type="text" wcpt-model-key="height" placeholder="42px" />
    </div>

    <!-- border -->
    <div class="wcpt-editor-row-option">
      <label>Border</label>
      <div class="wcpt-flex-option-container">
        <input type="text" wcpt-model-key="border-width" placeholder="1px">
        <input type="text" wcpt-model-key="border-color" class="wcpt-color-picker" placeholder="color">
      </div>
    </div>

    <!-- border-color:hover -->
    <div class="wcpt-editor-row-option">
      <label>↳ border color on hover</label>
      <input type="text" wcpt-model-key="border-color:hover" class="wcpt-color-picker">
    </div>

    <!-- border-radius -->
    <div class="wcpt-editor-row-option">
      <label>Border radius</label>
      <input type="text" wcpt-model-key="border-radius" placeholder="4px">
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

</div>

<div class="wcpt-editor-row-option" wcpt-model-key="style">

  <div class="wcpt-editor-row-option wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="[id] .wcpt-search-submit">

    <span class="wcpt-toggle-label">
      <?php echo wcpt_icon('paint-brush'); ?>
      Style for Submit Button
      <?php echo wcpt_icon('chevron-down'); ?>
    </span>

    <!-- background-color -->
    <div class="wcpt-editor-row-option">
      <label>Background color</label>
      <input type="text" wcpt-model-key="background" class="wcpt-color-picker">
    </div>

    <!-- border-radius -->
    <div class="wcpt-editor-row-option">
      <label>Border radius</label>
      <input type="text" wcpt-model-key="border-radius" placeholder="e.g. 4px">
    </div>

    <!-- color -->
    <div class="wcpt-editor-row-option">
      <label>Icon color</label>
      <input type="text" wcpt-model-key="color" class="wcpt-color-picker">
    </div>

  </div>

</div>