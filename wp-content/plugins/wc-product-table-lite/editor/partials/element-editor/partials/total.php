<!-- include total in cart -->
<div class="wcpt-editor-row-option">
  <label>
    Total calculation
  </label>
  <select wcpt-model-key="include_total_in_cart">
    <option value="">Only based on selected quantity</option>
    <option value="true">Also include amount already in cart</option>
  </select>
</div>

<!-- output template -->
<div class="wcpt-editor-row-option">
  <label>
    Template for the output
    <small>Use {n} as placeholder</small>
  </label>
  <input type="text" wcpt-model-key="output_template" />
</div>

<!-- no output template -->
<div class="wcpt-editor-row-option">
  <label>
    Template when there is no output
  </label>
  <input type="text" wcpt-model-key="no_output_template" />
</div>

<!-- variable switch -->
<!-- <div class="wcpt-editor-row-option">
  <label>
    <input type="checkbox" wcpt-model-key="variable_switch" />
    Switch total based on selected variation
  </label>
</div>   -->

<div class="wcpt-editor-row-option">
  <label>HTML Class</label>
  <input type="text" wcpt-model-key="html_class" />
</div>

<div class="wcpt-editor-row-option" wcpt-model-key="style">

  <!-- style for element -->
  <div class="wcpt-editor-row-option">
    <div class=" wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="[id]">

      <span class="wcpt-toggle-label">
        <?php echo wcpt_icon('paint-brush'); ?>
        Style for Total
        <?php echo wcpt_icon('chevron-down'); ?>
      </span>

      <?php require('style/common-props.php'); ?>

    </div>
  </div>

  <!-- style for empty -->
  <div class="wcpt-editor-row-option">
    <div class="wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="[id].wcpt-total--empty">

      <span class="wcpt-toggle-label">
        <?php echo wcpt_icon('paint-brush'); ?>
        Style for 'Empty' Total
        <?php echo wcpt_icon('chevron-down'); ?>
      </span>

      <!-- font-size -->
      <div class="wcpt-editor-row-option">
        <label>Font size</label>
        <input type="text" wcpt-model-key="font-size" />
      </div>

      <!-- font color -->
      <div class="wcpt-editor-row-option">
        <label>Font color</label>
        <input type="text" wcpt-model-key="color" placeholder="#000" class="wcpt-color-picker">
      </div>

      <!-- background color -->
      <div class="wcpt-editor-row-option">
        <label>Background color</label>
        <input type="text" wcpt-model-key="background-color" class="wcpt-color-picker">
      </div>

    </div>
  </div>
</div>

<!-- condition -->
<?php include('condition/outer.php'); ?>