<!-- template -->
<div class="wcpt-editor-row-option">
  <label>
    Number template
    <small>
      Use {n} as a placeholder for the actual number. For example: "Item #{n}" will display as "Item #1", "Item #2",
      etc.
    </small>
  </label>
  <input wcpt-model-key="template" type="text" placeholder="{n}">
</div>

<!-- min digits -->
<div class="wcpt-editor-row-option">
  <label>
    Minimum digits
    <small>
      The minimum number of digits to display. Numbers with fewer digits will be padded with leading zeros.
    </small>
  </label>
  <input wcpt-model-key="min_digits" type="number" min="1" max="10" placeholder="2">
</div>

<!-- style -->
<?php
$element_name = 'Position
 Number';
include('style/common.php');
?>

<!-- condition -->
<?php include('condition/outer.php'); ?>