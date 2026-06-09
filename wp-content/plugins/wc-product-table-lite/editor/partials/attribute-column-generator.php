<?php
$admin_url = admin_url('edit.php?post_type=product&page=product_attributes');
$attribute_slugs_message = 'Enter <a href="' . esc_url($admin_url) . '" target="_blank">global attribute</a> slugs, one per line.';
?>

<!-- attribute columns -->
<div class="wcpt-editor-row-option">
  <label style="padding-top: 0">
    <span style="font-weight: bold;">Auto-generate attribute columns</span>
    <?php wcpt_editor_tooltip('This is a special column type that you can use to automatically generate multiple attribute columns in the table. Only works with global woocommerce attributes.'); ?>
  </label>
  <hr style="border-bottom: 1px solid #ddd;
        border-top: none;
        background: none;
        margin: 10px 0 0;
        padding: 0;" />
</div>

<!-- attribute source -->
<div class="wcpt-editor-row-option">
  <label>
    Select attributes to generate columns
  </label>
  <label>
    <input type="radio" wcpt-model-key="attribute_source" value="auto">
    Auto – Based on products on the current table page
  </label>

  <?php wcpt_pro_radio('custom', 'Custom – Select a specific set of attributes', 'attribute_source'); ?>
  <label><small wcpt-panel-condition="prop" wcpt-condition-prop="attribute_source"
      wcpt-condition-val="custom"><?php echo $attribute_slugs_message; ?></small></label>
  <textarea wcpt-panel-condition="prop" wcpt-condition-prop="attribute_source" wcpt-condition-val="custom"
    wcpt-model-key="pre_selected_attribute_slugs"></textarea>

</div>

<!-- attribute order -->
<div class="wcpt-editor-row-option">
  <label>
    Select attribute column order
  </label>
  <label>
    <input type="radio" wcpt-model-key="attribute_order" value="most_used">
    Most used <?php wcpt_editor_tooltip('Based on the number of products on current page that have the attribute.'); ?>
  </label>
  <label>
    <input type="radio" wcpt-model-key="attribute_order" value="alphabetic">
    Alphabetic
  </label>
  <?php wcpt_pro_radio('custom', 'Custom order', 'attribute_order'); ?>
  <span style="width: 100%;" wcpt-panel-condition="prop" wcpt-condition-prop="attribute_order"
    wcpt-condition-val="auto">
    <small wcpt-panel-condition="prop" wcpt-condition-prop="attribute_order"
      wcpt-condition-val="custom"><?php echo $attribute_slugs_message; ?></small></span>
  <div wcpt-panel-condition="prop" wcpt-condition-prop="attribute_order" wcpt-condition-val="custom">
    <textarea wcpt-model-key="ordered_attribute_slugs" wcpt-panel-condition="prop" wcpt-condition-prop="attribute_order"
      wcpt-condition-val="custom"></textarea>
  </div>

</div>

<!-- exclude attributes -->
<label class="wcpt-editor-row-option">
  <label>
    Exclude attributes by slug <small><?php echo $attribute_slugs_message; ?></small>
  </label>
  <textarea wcpt-model-key="exclude_attributes"></textarea>
</label>

<!-- max attribute columns -->
<label class="wcpt-editor-row-option">
  <label>
    Maximum number of attribute columns to generate
  </label>
  <input type="number" wcpt-model-key="max_columns" min="1" max="20" placeholder="default: 3"
    data-wcpt-diw-disabled="true">
</label>

<!-- link term to filter -->
<div class="wcpt-editor-row-option">
  <label>
    Action when clicking an attribute terms:
  </label>
  <label><input type="radio" wcpt-model-key="click_action" value="">Do nothing</label>
  <?php wcpt_pro_radio('archive_redirect', 'Go to archive page', 'click_action'); ?>
  <?php wcpt_pro_radio('trigger_filter', 'Trigger matching filter', 'click_action'); ?>
  <label wcpt-panel-condition="prop" wcpt-condition-prop="click_action" wcpt-condition-val="trigger_filter">
    <small>
      Note: This option requires that you have the corresponding navigation filter element set up in your table's
      navigation section.
    </small>
  </label>
</div>

<!-- terms in separate lines -->
<div class="wcpt-editor-row-option">
  <label>
    <input type="checkbox" wcpt-model-key="separate_lines">
    Show multiple terms in separate lines
  </label>
</div>

<!-- term separator -->
<div class="wcpt-editor-row-option">
  <label>Separator between attribute terms</label>
  <div wcpt-model-key="separator" class="wcpt-separator-editor" wcpt-block-editor="" wcpt-be-add-row="0"></div>
</div>

<!-- empty value relabel -->
<div class="wcpt-editor-row-option">
  <label>Output when no attribute terms are found</label>
  <div wcpt-model-key="empty_relabel" wcpt-block-editor="" wcpt-be-add-row="0"></div>
</div>

<!-- exclude terms -->
<div class="wcpt-editor-row-option">
  <label>
    Exclude terms by slug
    <small><?php echo $attribute_slugs_message; ?></small>
  </label>
  <textarea wcpt-model-key="exclude_terms"></textarea>
</div>

<!-- enable headings -->
<div class="wcpt-editor-row-option">
  <label>
    <input type="checkbox" wcpt-model-key="heading_enabled">
    Show column heading with attribute name
  </label>
</div>

<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="heading_enabled"
  wcpt-condition-val="true">
  <!-- enable sort by attribute headings -->
  <div class="wcpt-editor-row-option">
    <?php wcpt_pro_checkbox('true', 'Sort products by attribute when the column heading is clicked', 'sort_by_column_heading_enabled'); ?>
  </div>

  <!-- numerical sorting attributes -->
  <div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="sort_by_column_heading_enabled"
    wcpt-condition-val="true">
    <label>
      Attributes that require numerical sorting
      <?php wcpt_editor_tooltip('Attribute terms need to be numbers or start with numbers like \'20 kg\', \'10 mm\' etc. The reverse (e.g., \'kg 20\', \'mm 10\') will not work.'); ?>
      <small>
        <?php echo $attribute_slugs_message; ?>
      </small>
    </label>
    <textarea wcpt-model-key="numerical_sorting_attributes"></textarea>
  </div>

  <!-- footer note -->
  <div class="wcpt-editor-row-option">
    <hr style="border-bottom: 1px solid #ddd;
    border-top: none;
    background: none;
    margin: 5px 0 20px;
    padding: 0;" />
    <label>
      <small>
        Note: This auto-attribute column generator facility works with <a
          href="https://woocommerce.com/document/managing-product-taxonomies/#how-to-add-edit-product-attributes"
          target="_blank">global woocommerce attributes</a> only, not custom - product level attributes.
      </small>
    </label>
  </div>

</div>