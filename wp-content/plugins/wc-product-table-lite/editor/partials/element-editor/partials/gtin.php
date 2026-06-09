<!-- variable switch -->
<div class="wcpt-editor-row-option">
  <?php wcpt_pro_checkbox('true', 'Switch GTIN/ISBN based on selected variation', 'variable_switch'); ?>
</div>

<!-- style -->
<?php
$element_name = 'GTIN';
include('style/common.php');
?>

<!-- condition -->
<?php include('condition/outer.php'); ?>