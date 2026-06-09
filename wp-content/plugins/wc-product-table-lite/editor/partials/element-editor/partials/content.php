<?php if (!empty($short_description)): ?>
  <!-- generate short description -->
  <div class="wcpt-editor-row-option">
    <label>
      <input type="checkbox" wcpt-model-key="generate">
      If product short description is missing use content excerpt
    </label>
  </div>
<?php endif; ?>

<!-- limit number of lines -->
<div class="wcpt-editor-row-option">
  <label>
    Limit content by
  </label>
  <label>
    <input type="radio" wcpt-model-key="limit_by" value="">
    Don't limit content
  </label>
  <label>
    <input type="radio" wcpt-model-key="limit_by" value="lines">
    Number of lines
  </label>
  <label>
    <input type="radio" wcpt-model-key="limit_by" value="words">
    Number of words
  </label>
</div>

<!--line clamp-->
<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="limit_by"
  wcpt-condition-val="lines">
  <label>
    Max number of lines
    <small>Note: This will limit the number of lines of the content.</small>
  </label>
  <input type="number" wcpt-model-key="line_clamp" min="1" />
</div>

<!-- clear all label -->
<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="limit_by"
  wcpt-condition-val="words">
  <label>
    Max number of words
    <small>Note: Using this option will strip HTML from the content.</small>
  </label>
  <input type="number" wcpt-model-key="limit" min="0" />
</div>

<!-- enable toggle -->
<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="limit_by"
  wcpt-condition-val="words">
  <div class="wcpt-editor-row-option">
    <?php
    wcpt_pro_checkbox(true, 'Show a toggle button to reveal more content', "toggle_enabled");
    ?>
  </div>

  <!-- toggle labels -->
  <div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="toggle_enabled"
    wcpt-condition-val="true">
    <!-- show more label -->
    <div class="wcpt-editor-row-option">
      <label>Show more label</label>
      <div wcpt-block-editor wcpt-model-key="show_more_label" wcpt-be-add-row="0"></div>
    </div>

    <!-- show less label -->
    <div class="wcpt-editor-row-option">
      <label>Show less label</label>
      <div wcpt-block-editor wcpt-model-key="show_less_label" wcpt-be-add-row="0"></div>
    </div>
  </div>
</div>

<?php if (empty($short_description)): ?>
  <!-- enable toggle -->
  <div class="wcpt-editor-row-option">
    <label>
      Action on any shortcodes found in content
    </label>
    <label>
      <input type="radio" wcpt-model-key="shortcode_action" value="">
      Process under no context after table is created (default)
    </label>
    <label>
      <input type="radio" wcpt-model-key="shortcode_action" value="strip">
      Remove all shortcodes from content (best performance)
    </label>
    <label>
      <input type="radio" wcpt-model-key="shortcode_action" value="process">
      Process each shortcode under individual product context
    </label>
  </div>
<?php endif; ?>

<!-- 'Read more' label -->
<div class="wcpt-editor-row-option <?php wcpt_pro_cover(); ?>" wcpt-panel-condition="prop"
  wcpt-condition-prop="toggle_enabled" wcpt-condition-val="false">
  <label>
    Label for 'Read more' link to product <?php wcpt_pro_badge(); ?>
    <small>Leave empty to hide the 'read more' link</small>
  </label>
  <div wcpt-model-key="read_more_label" wcpt-block-editor="" wcpt-be-add-row="0"></div>
</div>

<!-- HTML class -->
<div class="wcpt-editor-row-option">
  <label>HTML Class</label>
  <input type="text" wcpt-model-key="html_class" />
</div>

<!-- style -->
<div class="wcpt-editor-row-option" wcpt-model-key="style">

  <div class="wcpt-editor-row-option wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="[id]">

    <span class="wcpt-toggle-label">
      <?php echo wcpt_icon('paint-brush'); ?>
      <?php echo !empty($short_description) ? 'Style for Short Description' : 'Style for Content'; ?>
      <?php echo wcpt_icon('chevron-down'); ?>
    </span>

    <!-- font-size -->
    <div class="wcpt-editor-row-option">
      <label>Font size</label>
      <input type="text" wcpt-model-key="font-size" />
    </div>

    <!-- line-height -->
    <div class="wcpt-editor-row-option">
      <label>Line height</label>
      <input type="text" wcpt-model-key="line-height" placeholder="1.2em">
    </div>

    <!-- font color -->
    <div class="wcpt-editor-row-option">
      <label>Font color</label>
      <input type="text" wcpt-model-key="color" placeholder="#000" class="wcpt-color-picker">
    </div>

    <!-- font-family -->
    <div class="wcpt-editor-row-option">
      <label>Font family</label>
      <input type="text" wcpt-model-key="font-family" />
    </div>

    <!-- max-height -->
    <div class="wcpt-editor-row-option">
      <label>Max height</label>
      <input type="text" wcpt-model-key="max-height" />
    </div>

    <!-- width -->
    <div class="wcpt-editor-row-option">
      <label>Width</label>
      <input type="text" wcpt-model-key="width" />
    </div>

    <!-- max-width -->
    <div class="wcpt-editor-row-option">
      <label>Max width</label>
      <input type="text" wcpt-model-key="max-width" />
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

<!-- condition -->
<?php include('condition/outer.php'); ?>