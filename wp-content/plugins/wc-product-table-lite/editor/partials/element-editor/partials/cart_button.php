<!-- label -->
<div class="wcpt-editor-row-option">
  <label>
    Button text label
  </label>
  <input type="text" wcpt-model-key="label_text" />
</div>

<!-- label -->
<div class="wcpt-editor-row-option">
  <label>
    Button icon
  </label>
  <select wcpt-model-key="label_icon" style="width: 100%;">
    <option value="">None</option>
    <option value="shopping-cart">Shopping Cart</option>
    <option value="shopping-bag">Shopping Bag</option>
  </select>
</div>

<!-- link -->
<div class="wcpt-editor-row-option">
  <label>Action on button click</label>
  <select wcpt-model-key="link">
    <option value="cart_ajax"> Add to cart via AJAX</option>
    <option value="cart_refresh"> Add to cart and refresh page</option>
    <option value="cart_redirect"> Add to cart and redirect to cart page</option>
    <option value="cart_checkout"> Add to cart and redirect to checkout page</option>
  </select>
</div>

<div class="wcpt-editor-row-option">
  <label>HTML Class</label>
  <input type="text" wcpt-model-key="html_class" />
</div>

<div class="wcpt-editor-row-option" wcpt-model-key="style">

  <div class="wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="[id]">

    <span class="wcpt-toggle-label">
      <?php echo wcpt_icon('paint-brush'); ?>
      Style for Cart Button
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

    <!-- font color on hover -->
    <div class="wcpt-editor-row-option">
      <label>↳ color on hover</label>
      <input type="text" wcpt-model-key="color:hover" placeholder="#000" class="wcpt-color-picker">
    </div>

    <!-- font-weight -->
    <div class="wcpt-editor-row-option">
      <label>Font weight</label>
      <select wcpt-model-key="font-weight">
        <option value="">Auto</option>
        <option value="normal">Normal</option>
        <option value="bold">Bold</option>
        <option value="lighter">Light</option>
      </select>
    </div>

    <!-- background color -->
    <div class="wcpt-editor-row-option">
      <label>Background color</label>
      <input type="text" wcpt-model-key="background-color" class="wcpt-color-picker">
    </div>

    <!-- background color on hover -->
    <div class="wcpt-editor-row-option">
      <label>↳ color on hover</label>
      <input type="text" wcpt-model-key="background-color:hover" class="wcpt-color-picker">
    </div>

    <!-- border -->
    <div class="wcpt-editor-row-option wcpt-borders-style">
      <label>Border</label>
      <input type="text" wcpt-model-key="border-width" placeholder="width">
      <select wcpt-model-key="border-style">
        <option value="">Auto</option>
        <option value="solid">Solid</option>
        <option value="dashed">Dashed</option>
        <option value="dotted">Dotted</option>
        <option value="none">None</option>
      </select>
      <input type="text" wcpt-model-key="border-color" class="wcpt-color-picker" placeholder="color">
    </div>

    <!-- border-color on hover -->
    <div class="wcpt-editor-row-option">
      <label>Border color on hover</label>
      <input type="text" wcpt-model-key="border-color:hover" class="wcpt-color-picker" placeholder="color">
    </div>

    <!-- border-radius -->
    <div class="wcpt-editor-row-option">
      <label>Border radius</label>
      <input type="text" wcpt-model-key="border-radius">
    </div>

    <!-- stroke-width -->
    <div class="wcpt-editor-row-option">
      <label>Icon thickness</label>
      <input type="text" wcpt-model-key="stroke-width" placeholder="2px">
    </div>

    <!-- width -->
    <div class="wcpt-editor-row-option">
      <label>Force width</label>
      <input type="text" wcpt-model-key="width" />
    </div>

    <!-- padding -->
    <div class="wcpt-editor-row-option">
      <label>Padding</label>
      <div class="wcpt-flex-option-container">
        <input type="text" wcpt-model-key="padding-top" placeholder="top">
        <input type="text" wcpt-model-key="padding-right" placeholder="right">
        <input type="text" wcpt-model-key="padding-bottom" placeholder="bottom">
        <input type="text" wcpt-model-key="padding-left" placeholder="left">
      </div>
    </div>
  </div>

  <div class="wcpt-editor-row-option" wcpt-model-key="[id].wcpt-disabled">
    <div class="wcpt-toggle-options wcpt-row-accordion">

      <span class="wcpt-toggle-label">
        <?php echo wcpt_icon('paint-brush'); ?>
        Style when out of stock
        <?php echo wcpt_icon('chevron-down'); ?>
      </span>

      <!-- opacity -->
      <div class="wcpt-editor-row-option">
        <label>Opacity</label>
        <?php
        $arr = array(
          '.1' => '.1',
          '.2' => '.2',
          '.3' => '.3',
          '.4' => '.4',
          '.5' => '.5',
          '.6' => '.6',
          '.7' => '.7',
          '.8' => '.8',
          '.9' => '.9',
          '1' => '1',
        );

        foreach ($arr as $key => $val) {
          ?>
          <label class="wcpt-radio-wrapper">
            <input type="radio" name="wcpt-opacity" wcpt-model-key="opacity" value="<?php echo $val; ?>" />
            <?php echo $key; ?>
          </label>
          <?php
        }
        ?>
      </div>

      <!-- font color -->
      <div class="wcpt-editor-row-option">
        <label>Font color</label>
        <input type="text" wcpt-model-key="color" placeholder="#000" class="wcpt-color-picker">
      </div>

      <!-- font color on hover -->
      <div class="wcpt-editor-row-option">
        <label>↳ color on hover</label>
        <input type="text" wcpt-model-key="color:hover" placeholder="#000" class="wcpt-color-picker">
      </div>

      <!-- background color -->
      <div class="wcpt-editor-row-option">
        <label>Background color</label>
        <input type="text" wcpt-model-key="background-color" class="wcpt-color-picker">
      </div>

      <!-- background color on hover -->
      <div class="wcpt-editor-row-option">
        <label>↳ color on hover</label>
        <input type="text" wcpt-model-key="background-color:hover" class="wcpt-color-picker">
      </div>

      <!-- border-color -->
      <div class="wcpt-editor-row-option">
        <label>Border color</label>
        <input type="text" wcpt-model-key="border-color" class="wcpt-color-picker" placeholder="color">
      </div>

      <!-- border-color on hover -->
      <div class="wcpt-editor-row-option">
        <label>↳ color on hover</label>
        <input type="text" wcpt-model-key="border-color:hover" class="wcpt-color-picker" placeholder="color">
      </div>

    </div>
  </div>

  <div class="wcpt-editor-row-option" wcpt-model-key="[id] .wcpt-cart-badge-number">
    <div class="wcpt-toggle-options wcpt-row-accordion">

      <span class="wcpt-toggle-label">
        <?php echo wcpt_icon('paint-brush'); ?>
        Style for Cart Badge
        <?php echo wcpt_icon('chevron-down'); ?>
      </span>

      <!-- visibility -->
      <div class="wcpt-editor-row-option">
        <label>Visibility</label>
        <select wcpt-model-key="visibility">
          <option value=""></option>
          <option value="hidden">Hide</option>
          <option value="visible">Show</option>
        </select>
      </div>

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

      <!-- border -->
      <div class="wcpt-editor-row-option wcpt-borders-style">
        <label>Border</label>
        <input type="text" wcpt-model-key="border-width" placeholder="width">
        <select wcpt-model-key="border-style">
          <option value="">Auto</option>
          <option value="solid">Solid</option>
          <option value="dashed">Dashed</option>
          <option value="dotted">Dotted</option>
          <option value="none">None</option>
        </select>
        <input type="text" wcpt-model-key="border-color" class="wcpt-color-picker" placeholder="color">
      </div>

      <!-- padding -->
      <div class="wcpt-editor-row-option">
        <label>Padding</label>
        <div class="wcpt-flex-option-container">
          <input type="text" wcpt-model-key="padding-top" placeholder="top">
          <input type="text" wcpt-model-key="padding-right" placeholder="right">
          <input type="text" wcpt-model-key="padding-bottom" placeholder="bottom">
          <input type="text" wcpt-model-key="padding-left" placeholder="left">
        </div>
      </div>

    </div>
  </div>

</div>

<!-- condition -->
<?php include('condition/outer.php'); ?>