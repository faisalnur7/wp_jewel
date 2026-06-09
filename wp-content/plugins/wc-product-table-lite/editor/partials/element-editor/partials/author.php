<!--
<div class="wcpt-editor-row-option">
  <label>Link on author name</label>
  <select wcpt-model-key="link">
    <option value="">None</option>
    <option value="product_page">Product page</option>
    <?php wcpt_pro_option('custom_field', 'Custom field with url'); ?>
  </select>
</div>

<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="link"
  wcpt-condition-val="custom_field">
  <label>
    Custom field name
  </label>
  <input type="text" wcpt-model-key="custom_field" />
</div>

<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="link"
  wcpt-condition-val="custom_field">
  <label>
    <input type="checkbox" wcpt-model-key="custom_field_default_product_page" />
    Link to product page if custom field has no value
  </label>
</div>

<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="link"
  wcpt-condition-val="product_page||custom_field">
  <label>
    <input type="checkbox" wcpt-model-key="target_new_page" />
    Open the link on a new page
  </label>
</div>
-->

<!-- HTML tag -->
<!--
<div class="wcpt-editor-row-option">
  <label>HTML tag <?php wcpt_pro_badge(); ?></label>
  <div class="<?php wcpt_pro_cover(); ?>">
    <select wcpt-model-key="html_tag">
      <?php
      $options = array(
        'span' => 'span',
        'h1' => 'H1',
        'h2' => 'H2',
        'h3' => 'H3',
        'h4' => 'H4',
      );
      foreach ($options as $val => $label) {
        echo '<option value="' . $val . '">' . $label . '</option>';
      }
      ?>
    </select>
  </div>
</div>
-->

<!-- style -->
<?php
$element_name = 'Author';
include('style/common.php');
?>

<!-- condition -->
<?php include('condition/outer.php'); ?>