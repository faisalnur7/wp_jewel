<!-- display type -->
<div class="wcpt-editor-row-option">
  <label>
    Display type
  </label>
  <select wcpt-model-key="display_type">
    <option value="input">Input box (numeric input field)</option>
    <?php wcpt_pro_option('select', 'Dropdown (select field)'); ?>
  </select>
</div>

<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="display_type"
  wcpt-condition-val="select">
  <!-- max quantity -->
  <div class="wcpt-editor-row-option">
    <label>
      Max quantity (default)
    </label>
    <input type="number" wcpt-model-key="max_qty" />
  </div>

  <!-- quantity label -->
  <div class="wcpt-editor-row-option">
    <label>
      Quantity label
    </label>
    <input type="text" wcpt-model-key="qty_label" />
  </div>

</div>

<!-- initial value -->
<div class="wcpt-editor-row-option">
  <label>
    Initial quantity value
  </label>
  <select wcpt-model-key="initial_value">
    <option value="min">Minimum quantity</option>
    <?php wcpt_pro_option('0', '0'); ?>
    <?php wcpt_pro_option('empty', 'Empty'); ?>
  </select>
</div>

<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="display_type"
  wcpt-condition-val="input">

  <!-- controls -->
  <div class="wcpt-editor-row-option">
    <label>
      Show + and - control buttons
    </label>
    <select wcpt-model-key="controls">
      <option value="none">No buttons</option>
      <option value="browser">Use browser default</option>
      <?php wcpt_pro_option('left_edge', 'Left edge'); ?>
      <?php wcpt_pro_option('right_edge', 'Right edge'); ?>
      <?php wcpt_pro_option('edges', 'Edges'); ?>
    </select>
  </div>



  <?php wcpt_editor_more_options_container_start(); ?>

  <!-- height match -->
  <div class="wcpt-editor-row-option">
    <label>
      <input type="checkbox" wcpt-model-key="cart_button_height_match"> Match height with add to cart button
    </label>
  </div>

  <!-- width match -->
  <div class="wcpt-editor-row-option">
    <label>
      <input type="checkbox" wcpt-model-key="cart_button_width_match"> Match width with add to cart button
    </label>
  </div>

  <!-- return to initial value after add to cart -->
  <div class="wcpt-editor-row-option">
    <label>
      <input type="checkbox" wcpt-model-key="return_to_initial"> Return to initial value after add to cart (also
      clears checkbox)
    </label>
  </div>

  <div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="initial_value"
    wcpt-condition-val="min">
    <!-- return to minimum value when variation changes -->
    <div class="wcpt-editor-row-option">
      <label>
        <input type="checkbox" wcpt-model-key="reset_on_variation_change"> Reset to minimum quantity when variation
        is
        changed
      </label>
    </div>
  </div>

  <!-- max warning -->
  <div class="wcpt-editor-row-option">
    <label>
      Warning when user enters value exceeding min quantity
      <small>Use placeholder: [max]</small>
    </label>
    <input type="text" wcpt-model-key="qty_warning" />
  </div>

  <!-- min warning -->
  <div class="wcpt-editor-row-option">
    <label>
      Warning when user enters value below min quantity
      <small>Use placeholder: [min]</small>
    </label>
    <input type="text" wcpt-model-key="min_qty_warning" />
  </div>

  <!-- step warning -->
  <div class="wcpt-editor-row-option">
    <label>
      Warning when user enters value not following step requirement
      <small>Use placeholder: [step]</small>
    </label>
    <input type="text" wcpt-model-key="qty_step_warning" />
  </div>

  <!-- html title -->
  <div class="wcpt-editor-row-option">
    <label>
      Text for the HTML title attribute (default: "Quantity")
    </label>
    <input type="text" wcpt-model-key="html_title" />
  </div>


  <!-- hide if 1 limit order -->
  <div class="wcpt-editor-row-option">
    <?php wcpt_pro_checkbox('true', 'Hide if only 1 allowed per order', 'hide_if_sold_individually'); ?>
  </div>

  <?php wcpt_editor_more_options_container_end(); ?>

  <div class="wcpt-editor-row-option">
    <label>HTML class for container</label>
    <input type="text" wcpt-model-key="html_class" />
  </div>

  <div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="display_type"
    wcpt-condition-val="input">
    <div wcpt-model-key="style">

      <!-- quantity input -->
      <div class="wcpt-editor-row-option">
        <div class="wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="[id].wcpt-display-type-input">

          <span class="wcpt-toggle-label">
            <?php echo wcpt_icon('paint-brush'); ?>
            Style for Quantity
            <?php echo wcpt_icon('chevron-down'); ?>
          </span>

          <!-- font-size -->
          <div class="wcpt-editor-row-option">
            <label>Font size</label>
            <input type="text" wcpt-model-key="font-size" placeholder="16px">
          </div>

          <!-- font-color -->
          <div class="wcpt-editor-row-option">
            <label>Font color</label>
            <input type="text" wcpt-model-key="color" placeholder="#000" class="wcpt-color-picker">
          </div>

          <!-- background-color -->
          <div class="wcpt-editor-row-option">
            <label>Background color</label>
            <input type="text" wcpt-model-key="background-color" class="wcpt-color-picker">
          </div>

          <!-- border width -->
          <div class="wcpt-editor-row-option">
            <label>Border width</label>
            <input type="text" wcpt-model-key="border-width" placeholder="1px">
          </div>

          <!-- border color -->
          <div class="wcpt-editor-row-option">
            <label>Border color</label>
            <input type="text" wcpt-model-key="border-color" class="wcpt-color-picker" placeholder="#000000">
          </div>

          <!-- border radius -->
          <div class="wcpt-editor-row-option">
            <label>Border radius</label>
            <input type="text" wcpt-model-key="border-radius" placeholder="0px">
          </div>

          <!-- width -->
          <div class="wcpt-editor-row-option">
            <label>Width</label>
            <input type="text" wcpt-model-key="width" />
          </div>

          <!-- height -->
          <div class="wcpt-editor-row-option">
            <label>Height</label>
            <input type="text" wcpt-model-key="height" />
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
    </div>

    <div wcpt-model-key="style" wcpt-panel-condition="prop" wcpt-condition-prop="controls"
      wcpt-condition-val="left_edge||right_edge||edges">
      <!-- quantity + / - control buttons -->
      <div class="wcpt-editor-row-option">
        <div class="wcpt-toggle-options wcpt-row-accordion"
          wcpt-model-key="[id].wcpt-display-type-input .wcpt-qty-controller">

          <span class="wcpt-toggle-label">
            <?php echo wcpt_icon('paint-brush'); ?>
            Style for Quantity + / - Buttons
            <?php echo wcpt_icon('chevron-down'); ?>
          </span>

          <!-- font-size -->
          <div class="wcpt-editor-row-option">
            <label>Font size</label>
            <input type="text" wcpt-model-key="font-size" placeholder="16px">
          </div>

          <!-- font-color -->
          <div class="wcpt-editor-row-option">
            <label>Font color</label>
            <input type="text" wcpt-model-key="color" placeholder="#000" class="wcpt-color-picker">
          </div>

          <!-- background-color -->
          <div class="wcpt-editor-row-option">
            <label>Background color</label>
            <input type="text" wcpt-model-key="background-color" class="wcpt-color-picker">
          </div>

          <!-- border-color on hover -->
          <div class="wcpt-editor-row-option">
            <label>↳ Background color on hover</label>
            <input type="text" wcpt-model-key="background-color:hover" class="wcpt-color-picker" placeholder="color">
          </div>

          <!-- width -->
          <!-- <div class="wcpt-editor-row-option">
            <label>Width (px)</label>
            <input type="number" wcpt-model-key="width" />
          </div> -->

        </div>
      </div>

    </div>
  </div>

</div>

<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="display_type"
  wcpt-condition-val="select">
  <div wcpt-model-key="style">
    <div class="wcpt-editor-row-option wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="[id] > .wcpt-qty-select">

      <span class="wcpt-toggle-label">
        <?php echo wcpt_icon('paint-brush'); ?>
        Style for Select element
        <?php echo wcpt_icon('chevron-down'); ?>
      </span>

      <!-- font-size -->
      <div class="wcpt-editor-row-option">
        <label>Font size</label>
        <input type="text" wcpt-model-key="font-size" placeholder="16px">
      </div>

      <!-- width -->
      <div class="wcpt-editor-row-option">
        <label>Width</label>
        <input type="text" wcpt-model-key="width" />
      </div>

      <!-- height -->
      <div class="wcpt-editor-row-option">
        <label>Height</label>
        <input type="text" wcpt-model-key="height" />
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
</div>

<!-- condition -->
<?php include('condition/outer.php'); ?>