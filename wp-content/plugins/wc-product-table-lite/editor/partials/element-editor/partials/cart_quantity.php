<!-- template -->
<div class="wcpt-editor-row-option">
  <label>
    Template
    <small>
      Use {n} as a placeholder for the actual quantity in cart for this product. For example: "({n})" will display as
      "(2)", "(5)",
      etc.
    </small>
  </label>
  <input wcpt-model-key="template" type="text" placeholder="{n}">
</div>

<!-- empty template -->
<div class="wcpt-editor-row-option">
  <label>
    Empty template
    <small>
      This template will be displayed when the product is not in the cart.
    </small>
  </label>
  <input wcpt-model-key="empty_template" type="text" placeholder="">
</div>

<!-- style -->
<?php
$element_name = 'Cart quantity';
include('style/common.php');
?>

<!-- condition -->
<?php include('condition/outer.php'); ?>