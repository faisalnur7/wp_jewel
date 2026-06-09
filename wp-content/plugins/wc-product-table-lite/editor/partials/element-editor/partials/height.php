<!-- variable switch -->
<div class="wcpt-editor-row-option">
  <?php wcpt_pro_checkbox('true', 'Switch height based on selected variation', 'variable_switch'); ?>
</div>

<!-- template -->
<div class="wcpt-editor-row-option">
  <label>
    Height template
    <small>
      Use {n} as a placeholder for the actual product height.
    </small>
  </label>
  <input wcpt-model-key="template" type="text" placeholder="{n}">
</div>

<!-- empty template -->
<div class="wcpt-editor-row-option">
  <label>
    Output when no value
  </label>
  <input wcpt-model-key="empty_template" type="text">
</div>


<!-- style -->
<?php
$element_name = 'Height';
include('style/common.php');
?>

<!-- condition -->
<?php include('condition/outer.php'); ?>