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
    <option value="download">Download symbol</option>
    <option value="download-cloud">Download cloud symbol</option>
    <option value="arrow-down-circle">Arrow inside circle</option>
    <option value="file-plus">File with plus sign</option>
    <option value="file-text">File with text sign</option>
    <option value="file">File with no sign</option>
    <option value="music">Music note symbol</option>
    <option value="archive">Archive box</option>
    <option value="bookmark">Bookmark</option>
    <option value="calendar">Calendar</option>
    <option value="briefcase">Briefcase</option>
    <option value="star">Star</option>
    <option value="help-circle">Help circle</option>
    <option value="info">Info</option>
    <option value="key">Key</option>
    <option value="lock">Lock</option>
    <option value="mail">Mail</option>
    <option value="map-pin">Map pin</option>
  </select>
</div>

<!-- link -->
<div class="wcpt-editor-row-option">
  <label>Downloadable file source</label>
  <select wcpt-model-key="link">
    <option value="custom_field">Custom field</option>
    <option value="custom_field_media_id">Custom field with media ID</option>
    <option value="custom_field_acf">ACF managed custom field</option>
    <option value="custom">Custom url with placeholders</option>
  </select>
</div>

<!-- build custom url -->
<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="link" wcpt-condition-val="custom"
  style="margin-top: 0;">
  <label>Create custom url with placeholders</label>
  <input wcpt-model-key="custom_url" type="text">
  <label>
    <?php wcpt_general_placeholders__print_placeholders(); ?>
  </label>
</div>

<!-- custom field -->
<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="link"
  wcpt-condition-val="custom_field||custom_field_media_id||custom_field_acf">
  <label>Custom field name</label>
  <input wcpt-model-key="custom_field" type="text">
</div>

<div class="wcpt-editor-row-option">
  <label>HTML Class</label>
  <input type="text" wcpt-model-key="html_class" />
</div>

<div class="wcpt-editor-row-option" wcpt-model-key="style">

  <div class="wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="[id]">
    <span class="wcpt-toggle-label">
      <?php echo wcpt_icon('paint-brush'); ?>
      Style for Download Button
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
      <label>Font color on hover</label>
      <input type="text" wcpt-model-key="color:hover" placeholder="#000" class="wcpt-color-picker">
    </div>

    <!-- font-weight -->
    <div class="wcpt-editor-row-option">
      <label>Font weight</label>
      <select wcpt-model-key="font-weight">
        <option value="normal">Normal</option>
        <option value="bold">Bold</option>
        <option value="200">Light</option>
      </select>
    </div>

    <!-- background color -->
    <div class="wcpt-editor-row-option">
      <label>Background color</label>
      <input type="text" wcpt-model-key="background-color" class="wcpt-color-picker">
    </div>

    <!-- background color on hover -->
    <div class="wcpt-editor-row-option">
      <label>Background color on hover</label>
      <input type="text" wcpt-model-key="background-color:hover" class="wcpt-color-picker">
    </div>

    <!-- border -->
    <div class="wcpt-editor-row-option wcpt-borders-style">
      <label>Border</label>
      <input type="text" wcpt-model-key="border-width" placeholder="width">
      <select wcpt-model-key="border-style">
        <option value="">Default</option>
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

</div>

<!-- condition -->
<?php include('condition/outer.php'); ?>