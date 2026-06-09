<h2>Edit Cell Row</h2>

<!-- columns count -->
<div class="wcpt-editor-row-option">
  <label>Column count</label>
  <label><input type="radio" wcpt-model-key="column_count" value="1">1 column</label>
  <label><input type="radio" wcpt-model-key="column_count" value="2">2 columns</label>
</div>

<!-- columns -->
<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="column_count"
  wcpt-condition-val="2" wcpt-model-key="columns">

  <!-- column alignment -->
  <div class="wcpt-editor-row-option">
    <label>Column horizontal alignment</label>
    <select wcpt-model-key="horizontal_alignment">
      <option value="">Auto</option>
      <option value="justify">Justify</option>
      <option value="left">Left</option>
      <option value="center">Center</option>
      <option value="right">Right</option>
    </select>
  </div>

  <!-- column vertical alignment -->

  <div class="wcpt-editor-row-option">
    <label>Column vertical alignment</label>
    <select wcpt-model-key="vertical_alignment">
      <option value="">Auto</option>
      <option value="top">Top</option>
      <option value="center">Center</option>
      <option value="baseline">Baseline</option>
      <option value="bottom">Bottom</option>
    </select>
  </div>

  <!-- column width -->
  <div class="wcpt-editor-row-option">
    <label>Column width</label>
    <select wcpt-model-key="width">
      <option value="auto">Auto</option>
      <option value="equal">Equal (50% : 50%)</option>
    </select>
  </div>

  <!-- separator: later -->
</div>

<!-- HTML Class -->
<div class="wcpt-editor-row-option">
  <label>HTML Class</label>
  <input type="text" wcpt-model-key="html_class" />
</div>

<div class="wcpt-editor-row-option" wcpt-model-key="style">

  <div class="wcpt-editor-row-option wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="[id]">

    <span class="wcpt-toggle-label">
      <?php echo wcpt_icon('paint-brush'); ?>
      Style for Row
      <?php echo wcpt_icon('chevron-down'); ?>
    </span>

    <!-- margin-top -->
    <div class="wcpt-editor-row-option">
      <label>Gap above</label>
      <input type="text" wcpt-model-key="margin-top" class="wcpt-margin-input-force-full-width">
    </div>

    <!-- margin-bottom -->
    <div class="wcpt-editor-row-option">
      <label>Gap below</label>
      <input type="text" wcpt-model-key="margin-bottom" class="wcpt-margin-input-force-full-width">
    </div>

    <!-- gap between inner rows -->
    <div class="wcpt-editor-row-option">
      <label>Gap between inner rows</label>
      <input type="text" wcpt-model-key="--wcpt-inner-row-gap" placeholder="0px">
    </div>

    <!-- gap between inner columns -->
    <div class="wcpt-editor-row-option">
      <label>Gap between inner columns</label>
      <input type="text" wcpt-model-key="--wcpt-inner-column-gap" placeholder="10px">
    </div>

    <!-- white-space -->
    <div class="wcpt-editor-row-option">
      <label>Allow elements to wrap</label>
      <select wcpt-model-key="white-space">
        <option value="">Auto</option>
        <option value="normal">Yes, allow elements to wrap to new line</option>
        <option value="nowrap">No, force elements to stay in same line</option>
      </select>
    </div>

    <!-- font-size -->
    <div class="wcpt-editor-row-option">
      <label>Font size</label>
      <input type="text" wcpt-model-key="font-size">
    </div>

    <!-- color -->
    <div class="wcpt-editor-row-option">
      <label>Font color</label>
      <input type="text" wcpt-model-key="color" class="wcpt-color-picker">
    </div>

    <!-- text-align -->
    <div class="wcpt-editor-row-option">
      <label>Text align</label>
      <select wcpt-model-key="text-align">
        <option value="">Auto</option>
        <option value="left">Left</option>
        <option value="right">Right</option>
        <option value="center">Center</option>
        <option value="justify">Justify</option>
      </select>
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


  </div>

</div>

<!-- condition -->
<div class="wcpt-editor-row-option">

  <div class="wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="condition">

    <span class="wcpt-toggle-label">
      <?php echo wcpt_icon('shuffle'); ?>
      Condition for Row
      <?php wcpt_pro_badge(); ?>
      <?php echo wcpt_icon('chevron-down'); ?>
    </span>

    <?php require('condition/inner.php'); ?>
  </div>

</div>