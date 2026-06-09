<!-- <div class="wcpt-element-editor-tabs">
  <div class="wcpt-element-editor-tab-label">
    <span>Settings:</span>
  </div>
  <div class="wcpt-element-editor-tab" data-target="all" data-active="true">
    Show all
  </div>
  <div class="wcpt-element-editor-tab" data-target="styling">
    Styling
  </div>
  <div class="wcpt-element-editor-tab" data-target="conditions">
    Conditions
  </div>
</div> -->

<!-- click action -->
<div class="wcpt-editor-row-option">
  <label>
    Action on click
  </label>

  <select wcpt-model-key="click_action">
    <option value="">Do nothing</option>
    <option value="product_page">Open product page</option>
    <option value="product_page_new">Open product page in a new tab</option>
    <option value="image_page_new">Show full size image in a new tab</option>
    <?php wcpt_pro_option('lightbox', 'Display image in lightbox'); ?>
    <?php wcpt_pro_option('download', 'Download image'); ?>
  </select>
</div>

<!-- offset zoom enabled -->
<div class="wcpt-editor-row-option">
  <?php wcpt_pro_checkbox(true, 'Show magnified version of image on hover', 'offset_zoom_enabled'); ?>
</div>

<!-- more options -->
<?php wcpt_editor_more_options_container_start(); ?>

<!-- enable placeholder -->
<div class="wcpt-editor-row-option">
  <label>
    <input type="checkbox" wcpt-model-key="placeholder_enabled">
    Display placeholder if the image is not available
  </label>
</div>

<!-- product labels -->
<div class="wcpt-editor-row-option">
  <label>
    Product labels to display on image (top-left corner)
  </label>
  <div wcpt-model-key="product_labels" wcpt-block-editor wcpt-be-add-row="0"
    wcpt-be-add-element-partial="add-product-image-label-element"></div>
</div>

<!-- hover switch -->
<div class="wcpt-editor-row-option">
  <?php wcpt_pro_checkbox(true, 'Switch to first gallery image on hover', 'hover_switch_enabled'); ?>
</div>

<!-- image count -->
<div class="wcpt-editor-row-option">
  <?php wcpt_pro_checkbox(true, 'Display image gallery count in corner', 'image_count_enabled'); ?>
</div>

<!-- icon when -->
<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="click_action"
  wcpt-condition-val="lightbox">
  <label>
    Show lightbox magnifier icon on image
  </label>
  <select wcpt-model-key="icon_when">
    <option value="always">Always</option>
    <option value="row_hover">When row is hovered upon</option>
    <option value="image_hover">When image is hovered upon</option>
    <option value="image_hover_hide">Hide when image is hovered upon</option>
    <option value="never">Never</option>
  </select>
</div>

<!-- icon position -->
<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="click_action"
  wcpt-condition-val="lightbox">
  <div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="icon_when"
    wcpt-condition-val="always||row_hover||image_hover||image_hover_hide">
    <label>
      Magnifier icon position
    </label>
    <select wcpt-model-key="icon_position">
      <option value="bottom_right">Bottom right of image</option>
      <option value="outside_right">Outside image on right</option>
    </select>
  </div>

  <div class="wcpt-editor-row-option">
    <label>
      <input type="checkbox" wcpt-model-key="include_gallery">
      Include gallery images in lightbox
    </label>
  </div>
</div>

<!-- zoom trigger -->
<div class="wcpt-editor-row-option">
  <label>
    Zoom image when
    <?php wcpt_pro_badge(); ?>
  </label>
  <div class="<?php wcpt_pro_cover() ?>">
    <select wcpt-model-key="zoom_trigger">
      <option value="">Never</option>
      <option value="row_hover">Row is hovered upon</option>
      <option value="image_hover">Image is hovered upon</option>
    </select>
  </div>
</div>

<!-- zoom scale -->
<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="zoom_trigger"
  wcpt-condition-val="row_hover||image_hover">
  <label>
    Zoom scale level
  </label>
  <select wcpt-model-key="zoom_scale">
    <option value="1.02">1.02x</option>
    <option value="1.05">1.05x</option>
    <option value="1.1">1.1x</option>
    <option value="1.25">1.25x</option>
    <option value="1.5">1.5x</option>
    <option value="1.75">1.75x</option>
    <option value="2.0">2.0x</option>
    <option value="2.25">2.25x</option>
    <option value="2.5">2.5x</option>
    <option value="2.75">2.75x</option>
    <option value="3.0">3.0x</option>
    <option value="custom">Custom</option>
  </select>
</div>

<!-- zoom scale -->
<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="zoom_scale"
  wcpt-condition-val="custom">
  <label>
    Custom zoom scale level
    <small>Enter a decimal value like 3.0 without any alphabets.</small>
  </label>
  <input type="text" wcpt-model-key="custom_zoom_scale" />
</div>

<!-- size -->
<div class="wcpt-editor-row-option">
  <label>
    Image file source
  </label>
  <select wcpt-model-key="size">
    <option value="">Auto</option>
    <?php
    foreach (wcpt_get_all_image_sizes() as $image_size => $details) {
      echo "<option value='" . $image_size . "'>" . ucfirst(str_replace('_', ' ', $image_size)) . "</option>";
    }
    ?>
  </select>
</div>

<?php wcpt_editor_more_options_container_end(); ?>


<div class="wcpt-editor-row-option">
  <label>HTML Class</label>
  <input type="text" wcpt-model-key="html_class" />
</div>

<div class="wcpt-editor-row-option">
  <div wcpt-model-key="style">
    <div class="wcpt-editor-row-option wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="[id]">

      <span class="wcpt-toggle-label">
        <?php echo wcpt_icon('paint-brush'); ?>
        Style for Product Image
        <?php echo wcpt_icon('chevron-down'); ?>
      </span>

      <!-- width -->
      <!-- <div class="wcpt-editor-row-option">
        <label>Width</label>
        <input type="text" wcpt-model-key="width" />
      </div> -->

      <!-- height -->
      <!-- <div class="wcpt-editor-row-option">
        <label>Height</label>
        <input type="text" wcpt-model-key="height" />
      </div> -->

      <!-- max-width -->
      <div class="wcpt-editor-row-option">
        <label>
          Width
        </label>
        <input type="text" wcpt-model-key="max-width" />
      </div>

      <!-- height -->
      <!-- <div class="wcpt-editor-row-option">
        <label>Height</label>
        <input type="text" wcpt-model-key="height" />
      </div> -->

      <!-- aspect ratio -->
      <div class="wcpt-editor-row-option">
        <label>
          Aspect ratio
        </label>
        <select wcpt-model-key="aspect-ratio">
          <option value="">Auto</option>
          <option value="1">1:1</option>
          <option value="1.777777778">16:9</option>
          <option value="1.333333333">4:3</option>
          <option value="0.75">3:4</option>
          <option value="0.5625">9:16</option>
        </select>
      </div>

      <!-- object-fit -->
      <div class="wcpt-editor-row-option">
        <label>Object fit</label>
        <select wcpt-model-key="object-fit">
          <option value="">Auto</option>
          <option value="cover">Cover</option>
          <option value="contain">Contain</option>
          <option value="fill">Fill</option>
          <option value="none">None</option>
          <option value="scale-down">Scale down</option>
        </select>
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

      <!-- border-radius -->
      <div class="wcpt-editor-row-option">
        <label>Border radius</label>
        <input type="text" wcpt-model-key="border-radius">
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

<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="click_action"
  wcpt-condition-val="lightbox">

  <!-- lightbox icon -->
  <div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="icon_when"
    wcpt-condition-val="always||row_hover||image_hover||image_hover_hide">
    <div wcpt-model-key="style">
      <div class="wcpt-editor-row-option wcpt-toggle-options wcpt-row-accordion"
        wcpt-model-key="[id] > .wcpt-lightbox-icon">

        <span class="wcpt-toggle-label">
          <?php echo wcpt_icon('paint-brush'); ?>
          Style for Magnifier Icon
          <?php echo wcpt_icon('chevron-down'); ?>
        </span>

        <!-- color -->
        <div class="wcpt-editor-row-option">
          <label>Color</label>
          <input type="text" wcpt-model-key="color" />
        </div>

        <!-- background-color -->
        <div class="wcpt-editor-row-option">
          <label>Background color</label>
          <input type="text" wcpt-model-key="background-color" />
        </div>

        <!-- size -->
        <div class="wcpt-editor-row-option">
          <label>Size (px)</label>
          <input type="number" wcpt-model-key="font-size" />
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

        <!-- border-radius -->
        <div class="wcpt-editor-row-option">
          <label>Border radius</label>
          <input type="text" wcpt-model-key="border-radius">
        </div>

      </div>
    </div>

  </div>

  <!-- lightbox -->
  <div class="wcpt-editor-row-option">
    <!-- <div wcpt-model-key="style"> -->
    <div class="wcpt-editor-row-option wcpt-toggle-options wcpt-row-accordion">

      <span class="wcpt-toggle-label">
        <?php echo wcpt_icon('paint-brush'); ?>
        Style for LightBox
        <?php echo wcpt_icon('chevron-down'); ?>
      </span>

      <div class="wcpt-editor-row-option">
        <label>Color theme:</label>
        <label>
          <input value="black" type="radio" wcpt-model-key="lightbox_color_theme"> Black
        </label>
        <label>
          <input value="white" type="radio" wcpt-model-key="lightbox_color_theme"> White
        </label>
      </div>

    </div>
    <!-- </div> -->
  </div>

</div>

<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="offset_zoom_enabled"
  wcpt-condition-val="true">
  <div wcpt-model-key="style">
    <div class="wcpt-editor-row-option wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="[id]--offset-zoom-image">

      <span class="wcpt-toggle-label">
        <?php echo wcpt_icon('paint-brush'); ?>
        Style for Magnified Image
        <?php echo wcpt_icon('chevron-down'); ?>
      </span>

      <!-- max-width -->
      <div class="wcpt-editor-row-option">
        <label>
          Max width
          <small>Image width can be smaller but will never exceed this value</small>
        </label>
        <input type="text" wcpt-model-key="max-width" />
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

      <!-- border-radius -->
      <div class="wcpt-editor-row-option">
        <label>Border radius</label>
        <input type="text" wcpt-model-key="border-radius">
      </div>

      <!-- background-color -->
      <div class="wcpt-editor-row-option">
        <label>Background color</label>
        <input type="text" wcpt-model-key="background-color" />
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