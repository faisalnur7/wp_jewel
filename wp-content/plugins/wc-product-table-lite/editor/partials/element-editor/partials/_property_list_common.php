<!-- layout -->
<div class="wcpt-editor-row-option">
  <label>
    Layout for the properties
  </label>
  <label><input type="radio" wcpt-model-key="layout" value="row">Row</label>
  <label><input type="radio" wcpt-model-key="layout" value="column">Column</label>
  <?php wcpt_pro_radio('grid', 'Grid', 'layout'); ?>
  <?php wcpt_pro_radio('table', 'Table', 'layout'); ?>
  <?php wcpt_pro_radio('bar', 'Bar', 'layout'); ?>
  <?php wcpt_pro_radio('justified', 'Justified', 'layout'); ?>
</div>

<!-- columns for each layout -->
<?php foreach (['column', 'grid', 'table', 'justified'] as $layout): ?>
  <div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="layout"
    wcpt-condition-val="<?php echo $layout; ?>">
    <label>
      Number of columns for
      <?php echo $layout; ?> layout
      <?php wcpt_pro_badge(); ?>
    </label>
    <select <?php if (!defined('WCPT_PRO'))
      echo 'disabled'; ?> wcpt-model-key="<?php echo $layout; ?>_columns">
      <option value="1">1 column</option>
      <option value="2">2 columns</option>
      <option value="3">3 columns</option>
      <option value="4">4 columns</option>
      <option value="5">5 columns</option>
      <option value="6">6 columns</option>
    </select>
  </div>

<?php endforeach; ?>

<!-- row layout characters -->
<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="layout" wcpt-condition-val="row">
  <div class="wcpt-editor-row-option">
    <label>Character to use for the name/value separator</label>
    <input type="text" wcpt-model-key="row_layout_name_value_separator_character" />
  </div>
</div>

<!-- column layout characters -->
<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="layout"
  wcpt-condition-val="column">
  <div class="wcpt-editor-row-option">
    <label>Character to use for the name/value separator</label>
    <input type="text" wcpt-model-key="column_layout_name_value_separator_character" />
  </div>
</div>

<!-- table layout characters -->
<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="layout" wcpt-condition-val="table">
  <div class="wcpt-editor-row-option">
    <label>Character to use for the name/value separator</label>
    <input type="text" wcpt-model-key="table_layout_name_value_separator_character" />
  </div>
</div>

<!-- justified layout characters -->
<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="layout"
  wcpt-condition-val="justified">
  <div class="wcpt-editor-row-option">
    <label>Character to use for the name/value separator</label>
    <input type="text" wcpt-model-key="justified_layout_name_value_separator_character" />
  </div>
</div>

<!-- bar layout flip name and value -->
<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="layout" wcpt-condition-val="bar">
  <div class="wcpt-editor-row-option">
    <label>
      <input type="checkbox" wcpt-model-key="bar_layout_flip_name_and_value">
      Show property values above property names in bar layout
    </label>
  </div>
</div>

<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="layout" wcpt-condition-val="!bar">
  <!-- initial reveal -->
  <div class="wcpt-editor-row-option">
    <label>Number of properties to reveal initially <small>Leave empty to reveal all or
        select a number to show toggle
        button</small></label>
    <input type="number" wcpt-model-key="initial_reveal" min="1">
  </div>

  <div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="initial_reveal"
    wcpt-condition-val="true">

    <!-- show more label -->
    <div class="wcpt-editor-row-option">
      <label>Label for the 'Show more' button</label>
      <input type="text" wcpt-model-key="show_more_label" />
    </div>

    <!-- show less label -->
    <div class="wcpt-editor-row-option">
      <label>Label for the 'Show less' button</label>
      <input type="text" wcpt-model-key="show_less_label" />
    </div>
  </div>
</div>

<!-- full width -->
<?php foreach (['column', 'grid', 'justified', 'table'] as $layout): ?>
  <div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="layout"
    wcpt-condition-val="<?php echo $layout; ?>">
    <label>
      <input type="checkbox" wcpt-model-key="<?php echo $layout; ?>_full_width">
      <span>Force layout to be full width
        <?php echo $layout != 'table' ? ' and equal columns' : ''; ?>
      </span>
    </label>
  </div>
<?php endforeach; ?>

<!-- border and padding -->
<?php foreach (['row', 'column', 'grid', 'bar', 'justified'] as $layout): ?>
  <div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="layout"
    wcpt-condition-val="<?php echo $layout; ?>">
    <label>
      <input type="checkbox" wcpt-model-key="<?php echo $layout; ?>_border_and_padding">
      Enable border and padding for the container
    </label>
  </div>
<?php endforeach; ?>

<!-- only border top and bottom -->
<?php foreach (['column', 'justified', 'row', 'grid'] as $layout): ?>
  <div wcpt-panel-condition="prop" wcpt-condition-prop="layout" wcpt-condition-val="<?php echo $layout; ?>">
    <div class="wcpt-editor-row-option" wcpt-panel-condition="prop"
      wcpt-condition-prop="<?php echo $layout; ?>_border_and_padding" wcpt-condition-val="true">
      <label>
        <input type="checkbox" wcpt-model-key="<?php echo $layout; ?>_only_border_top_and_bottom">
        Only show the top and bottom borders
      </label>
    </div>
  </div>
<?php endforeach; ?>

<!-- row hide property name -->
<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="layout" wcpt-condition-val="row">
  <div class="wcpt-editor-row-option">
    <label>
      <input type="checkbox" wcpt-model-key="row_hide_property_names" />
      Hide the property names and show only the values
    </label>
  </div>
</div>

<!-- separator lines between columns -->
<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="layout"
  wcpt-condition-val="column||justified">
  <label>
    <input type="checkbox" wcpt-model-key="column_separator">
    Show separator line between columns
  </label>
</div>

<!-- separator lines between column rows -->
<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="layout"
  wcpt-condition-val="column">
  <label>
    <input type="checkbox" wcpt-model-key="column_row_separator">
    Show separator lines between column inner rows
  </label>
</div>

<!-- disable wrapping -->
<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="layout"
  wcpt-condition-val="column||justified">
  <label>
    <input type="checkbox" wcpt-model-key="disable_wrapping">
    Disable wrapping of property name and value
  </label>
</div>

<!-- HTML Class -->
<div class="wcpt-editor-row-option">
  <label>HTML Class</label>
  <input type="text" wcpt-model-key="html_class" />
</div>

<?php ob_start(); ?>
<!-- prop name -->
<div class="wcpt-editor-row-option">
  <div class="wcpt-toggle-options wcpt-row-accordion">

    <span class="wcpt-toggle-label">
      <?php echo wcpt_icon('paint-brush'); ?>
      Style for Property Name
      <?php echo wcpt_icon('chevron-down'); ?>
    </span>

    <!-- property name -->
    <div>
      <!-- font-size -->
      <div class="wcpt-editor-row-option">
        <label>Font size</label>
        <input type="text" wcpt-model-key="--wcpt-property-list-{layout}-property-name-font-size" />
      </div>

      <!-- font color -->
      <div class="wcpt-editor-row-option">
        <label>Font color</label>
        <input type="text" wcpt-model-key="--wcpt-property-list-{layout}-property-name-text-color" placeholder="#000"
          class="wcpt-color-picker">
      </div>

      <!-- font-weight -->
      <div class="wcpt-editor-row-option">
        <label>Font weight</label>
        <select wcpt-model-key="--wcpt-property-list-{layout}-property-name-font-weight">
          <option value="">Auto</option>
          <option value="normal">Normal</option>
          <option value="bold">Bold</option>
          <option value="200">Light</option>
        </select>
      </div>

      <!-- text-transform -->
      <div class="wcpt-editor-row-option">
        <label>Text transform</label>
        <select wcpt-model-key="--wcpt-property-list-{layout}-property-name-text-transform">
          <option value="" default>Auto</option>
          <option value="uppercase">Uppercase</option>
          <option value="lowercase">Lowercase</option>
          <option value="capitalize">Capitalize</option>
          <option value="none">None</option>
        </select>
      </div>

      <!-- background color -->
      <div class="wcpt-editor-row-option">
        <label>Background color</label>
        <input type="text" wcpt-model-key="--wcpt-property-list-{layout}-property-name-background-color"
          class="wcpt-color-picker">
      </div>

    </div>

    <!-- icon -->
    <div class="wcpt-editor-row-option">
      <div class="wcpt-toggle-options wcpt-row-accordion">

        <span class="wcpt-toggle-label">
          <?php echo wcpt_icon('paint-brush'); ?>
          Style for Icon in Name
          <span class="wcpt-tooltip" data-wcpt-direction="bottom" style="margin-left: 8px;
    position: relative;
    top: -1px;">
            <span class="wcpt-tooltip-icon"><?php wcpt_icon('help-circle'); ?></span>
            <span class="wcpt-tooltip-content">If you've created attribute relabels above and added icons, then you can
              style them here.</span>
          </span>
          <?php echo wcpt_icon('chevron-down'); ?>
        </span>

        <!-- font-size -->
        <div class="wcpt-editor-row-option">
          <label>Size</label>
          <input type="text" wcpt-model-key="--wcpt-property-list-{layout}-property-name-icon-size">
        </div>

        <!-- stroke-width -->
        <div class="wcpt-editor-row-option">
          <label>Stroke thickness</label>
          <input type="text" wcpt-model-key="--wcpt-property-list-{layout}-property-name-icon-stroke-thickness">
        </div>

        <!-- font-color -->
        <div class="wcpt-editor-row-option">
          <label>Color</label>
          <input type="text" wcpt-model-key="--wcpt-property-list-{layout}-property-name-icon-color" placeholder="#000"
            class="wcpt-color-picker">
        </div>

        <!-- fill -->
        <div class="wcpt-editor-row-option">
          <label>Fill color</label>
          <input type="text" wcpt-model-key="--wcpt-property-list-{layout}-property-name-icon-fill-color"
            class="wcpt-color-picker">
        </div>

      </div>
    </div>

  </div>

</div>

<!-- prop value -->
<div class="wcpt-editor-row-option">
  <div class="wcpt-toggle-options wcpt-row-accordion">

    <span class="wcpt-toggle-label">
      <?php echo wcpt_icon('paint-brush'); ?>
      Style for Property Value
      <?php echo wcpt_icon('chevron-down'); ?>
    </span>

    <!-- font-size -->
    <div class="wcpt-editor-row-option">
      <label>Font size</label>
      <input type="text" wcpt-model-key="--wcpt-property-list-{layout}-property-value-font-size" />
    </div>

    <!-- font color -->
    <div class="wcpt-editor-row-option">
      <label>Font color</label>
      <input type="text" wcpt-model-key="--wcpt-property-list-{layout}-property-value-text-color" placeholder="#000"
        class="wcpt-color-picker">
    </div>

    <!-- font-weight -->
    <div class="wcpt-editor-row-option">
      <label>Font weight</label>
      <select wcpt-model-key="--wcpt-property-list-{layout}-property-value-font-weight">
        <option value="">Auto</option>
        <option value="normal">Normal</option>
        <option value="bold">Bold</option>
        <option value="200">Light</option>
      </select>
    </div>

    <!-- background color -->
    <div class="wcpt-editor-row-option">
      <label>Background color</label>
      <input type="text" wcpt-model-key="--wcpt-property-list-{layout}-property-value-background-color"
        class="wcpt-color-picker">
    </div>

  </div>
</div>
<?php $property_name_value_style_options = ob_get_clean(); ?>

<!-- row layout -->
<div class="wcpt-editor-row-option" wcpt-model-key="style" wcpt-panel-condition="prop" wcpt-condition-prop="layout"
  wcpt-condition-val="row">
  <div class="wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="[id].wcpt-property-list--row-layout">
    <span class="wcpt-toggle-label">
      <?php echo wcpt_icon('paint-brush'); ?>
      Style for Row Layout
      <?php echo wcpt_icon('chevron-down'); ?>
    </span>

    <!-- gap between property name and value -->
    <div class="wcpt-editor-row-option">
      <label>Gap between property name and value</label>
      <input type="text" wcpt-model-key="--wcpt-property-list-row-gap-between-name-and-value">
    </div>

    <!-- gap between properties -->
    <div class="wcpt-editor-row-option">
      <label>Gap between properties</label>
      <input type="text" wcpt-model-key="--wcpt-property-list-row-gap-between-properties">
    </div>

    <!-- line height -->
    <div class="wcpt-editor-row-option">
      <label>Line height</label>
      <input type="text" wcpt-model-key="--wcpt-property-list-row-line-height" placeholder="inherit">
    </div>

    <!-- property separator -->
    <div class="wcpt-editor-row-option">
      <label>Separator between properties</label>
      <div class="wcpt-flex-option-container">
        <input type="text" wcpt-model-key="--wcpt-property-list-row-separator-width" placeholder="thickness (px)">
        <input type="text" min="10" max="100" wcpt-model-key="--wcpt-property-list-row-separator-height"
          placeholder="height (default:1em)">
        <input type="text" class="wcpt-color-picker" wcpt-model-key="--wcpt-property-list-row-separator-color"
          placeholder="color">
      </div>
    </div>

    <!-- padding -->
    <div class="wcpt-editor-row-option">
      <label>Padding</label>
      <div class="wcpt-flex-option-container">
        <input type="text" wcpt-model-key="--wcpt-property-list-row-padding-top" placeholder="top">
        <input type="text" wcpt-model-key="--wcpt-property-list-row-padding-right" placeholder="right">
        <input type="text" wcpt-model-key="--wcpt-property-list-row-padding-bottom" placeholder="bottom">
        <input type="text" wcpt-model-key="--wcpt-property-list-row-padding-left" placeholder="left">
      </div>
    </div>

    <!-- gap above -->
    <div class="wcpt-editor-row-option">
      <label>Gap above</label>
      <input type="text" wcpt-model-key="--wcpt-property-list-row-gap-above">
    </div>

    <!-- gap below -->
    <div class="wcpt-editor-row-option">
      <label>Gap below</label>
      <input type="text" wcpt-model-key="--wcpt-property-list-row-gap-below">
    </div>

    <!-- border -->
    <div class="wcpt-editor-row-option">
      <label>Border</label>
      <div class="wcpt-flex-option-container">
        <input type="text" wcpt-model-key="--wcpt-property-list-row-border-width" placeholder="thickness (px)">
        <select wcpt-model-key="--wcpt-property-list-row-border-style">
          <option value="" selected>Auto</option>
          <option value="solid">Solid</option>
          <option value="dashed">Dashed</option>
          <option value="dotted">Dotted</option>
        </select>
        <input type="text" wcpt-model-key="--wcpt-property-list-row-border-color" class="wcpt-color-picker"
          placeholder="color">
      </div>
    </div>

    <!-- border radius -->
    <div class="wcpt-editor-row-option">
      <label>Border radius</label>
      <input type="text" wcpt-model-key="--wcpt-property-list-row-border-radius" placeholder="6px">
    </div>

    <!-- background color -->
    <div class="wcpt-editor-row-option">
      <label>Background color</label>
      <input type="text" wcpt-model-key="--wcpt-property-list-row-background-color" class="wcpt-color-picker">
    </div>

    <?php echo str_replace('{layout}', 'row', $property_name_value_style_options); ?>

  </div>
</div>

<!-- column layout -->
<div class="wcpt-editor-row-option" wcpt-model-key="style" wcpt-panel-condition="prop" wcpt-condition-prop="layout"
  wcpt-condition-val="column">
  <div class="wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="[id].wcpt-property-list--column-layout">
    <span class="wcpt-toggle-label">
      <?php echo wcpt_icon('paint-brush'); ?>
      Style for Column Layout
      <?php echo wcpt_icon('chevron-down'); ?>
    </span>

    <!-- gap between property name and value -->
    <div class="wcpt-editor-row-option">
      <label>Gap between property name and value</label>
      <input type="text" wcpt-model-key="--wcpt-property-list-column-gap-between-name-and-value" placeholder="10px">
    </div>

    <!-- gap between property rows -->
    <div class="wcpt-editor-row-option">
      <label>Gap between property rows</label>
      <input type="text" wcpt-model-key="--wcpt-property-list-column-gap-between-rows" placeholder="10px">
    </div>

    <!-- gap between columns -->
    <div class="wcpt-editor-row-option">
      <label>Gap between columns</label>
      <input type="text" wcpt-model-key="--wcpt-property-list-column-gap-between-columns" placeholder="10px">
    </div>

    <!-- column separator -->
    <div class="wcpt-editor-row-option">
      <label>Column separator</label>
      <div class="wcpt-flex-option-container">
        <input type="text" wcpt-model-key="--wcpt-property-list-column-separator-width" placeholder="thickness (px)">
        <input type="text" min="10" max="100" wcpt-model-key="--wcpt-property-list-column-separator-height"
          placeholder="height (default:80%)">
        <input type="text" class="wcpt-color-picker" wcpt-model-key="--wcpt-property-list-column-separator-color"
          placeholder="color">
      </div>
    </div>

    <!-- row separator -->
    <div class="wcpt-editor-row-option">
      <label>Column row separator</label>
      <div class="wcpt-flex-option-container">
        <input type="text" wcpt-model-key="--wcpt-property-list-column-row-separator-height" placeholder="height (px)">
        <input type="text" wcpt-model-key="--wcpt-property-list-column-row-separator-width"
          placeholder="width (default:100%)">
        <input type="text" class="wcpt-color-picker" wcpt-model-key="--wcpt-property-list-column-row-separator-color"
          placeholder="color">
      </div>
    </div>

    <!-- padding -->
    <div class="wcpt-editor-row-option">
      <label>Padding</label>
      <div class="wcpt-flex-option-container">
        <input type="text" wcpt-model-key="--wcpt-property-list-column-padding-top" placeholder="top">
        <input type="text" wcpt-model-key="--wcpt-property-list-column-padding-right" placeholder="right">
        <input type="text" wcpt-model-key="--wcpt-property-list-column-padding-bottom" placeholder="bottom">
        <input type="text" wcpt-model-key="--wcpt-property-list-column-padding-left" placeholder="left">
      </div>
    </div>

    <!-- gap above -->
    <div class="wcpt-editor-row-option">
      <label>Gap above</label>
      <input type="text" wcpt-model-key="--wcpt-property-list-column-gap-above">
    </div>

    <!-- gap below -->
    <div class="wcpt-editor-row-option">
      <label>Gap below</label>
      <input type="text" wcpt-model-key="--wcpt-property-list-column-gap-below">
    </div>

    <!-- border -->
    <div class="wcpt-editor-row-option">
      <label>Border</label>
      <div class="wcpt-flex-option-container">

        <input type="text" wcpt-model-key="--wcpt-property-list-column-border-width" placeholder="thickness (px)">
        <select wcpt-model-key="--wcpt-property-list-column-border-style">
          <option value="" selected>Auto</option>
          <option value="solid">Solid</option>
          <option value="dashed">Dashed</option>
          <option value="dotted">Dotted</option>
        </select>
        <input type="text" wcpt-model-key="--wcpt-property-list-column-border-color" class="wcpt-color-picker"
          placeholder="color">
      </div>
    </div>

    <!-- border radius -->
    <div class="wcpt-editor-row-option">
      <label>Border radius</label>
      <input type="text" wcpt-model-key="--wcpt-property-list-column-border-radius" placeholder="6px">
    </div>

    <!-- background color -->
    <div class="wcpt-editor-row-option">
      <label>Background color</label>
      <input type="text" wcpt-model-key="--wcpt-property-list-column-background-color" class="wcpt-color-picker">
    </div>

    <!-- container width -->
    <div class="wcpt-editor-row-option">
      <label>Container width</label>
      <input type="text" wcpt-model-key="width" placeholder="auto">
    </div>

    <?php echo str_replace('{layout}', 'column', $property_name_value_style_options); ?>

  </div>
</div>

<!-- grid layout -->
<div class="wcpt-editor-row-option" wcpt-model-key="style" wcpt-panel-condition="prop" wcpt-condition-prop="layout"
  wcpt-condition-val="grid">
  <div class="wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="[id].wcpt-property-list--grid-layout">
    <span class="wcpt-toggle-label">
      <?php echo wcpt_icon('paint-brush'); ?>
      Style for Grid Layout
      <?php echo wcpt_icon('chevron-down'); ?>
    </span>

    <!-- gap between property name and value -->
    <div class="wcpt-editor-row-option">
      <label>Gap between property name and value</label>
      <input type="text" wcpt-model-key="--wcpt-property-list-grid-gap-between-name-and-value" placeholder="4px">
    </div>

    <!-- gap between columns -->
    <div class="wcpt-editor-row-option">
      <label>Gap between columns</label>
      <input type="text" wcpt-model-key="--wcpt-property-list-grid-gap-between-columns" placeholder="10px">
    </div>

    <!-- gap between rows -->
    <div class="wcpt-editor-row-option">
      <label>Gap between rows</label>
      <input type="text" wcpt-model-key="--wcpt-property-list-grid-gap-between-rows" placeholder="10px">
    </div>

    <!-- padding -->
    <div class="wcpt-editor-row-option">
      <label>Padding</label>
      <div class="wcpt-flex-option-container">
        <input type="text" wcpt-model-key="--wcpt-property-list-grid-padding-top" placeholder="top">
        <input type="text" wcpt-model-key="--wcpt-property-list-grid-padding-right" placeholder="right">
        <input type="text" wcpt-model-key="--wcpt-property-list-grid-padding-bottom" placeholder="bottom">
        <input type="text" wcpt-model-key="--wcpt-property-list-grid-padding-left" placeholder="left">
      </div>
    </div>

    <!-- gap above -->
    <div class="wcpt-editor-row-option">
      <label>Gap above</label>
      <input type="text" wcpt-model-key="--wcpt-property-list-grid-gap-above">
    </div>

    <!-- gap below -->
    <div class="wcpt-editor-row-option">
      <label>Gap below</label>
      <input type="text" wcpt-model-key="--wcpt-property-list-grid-gap-below">
    </div>

    <!-- border -->
    <div class="wcpt-editor-row-option">
      <label>Border</label>
      <div class="wcpt-flex-option-container">
        <input type="text" wcpt-model-key="--wcpt-property-list-grid-border-width" placeholder="thickness (px)">
        <select wcpt-model-key="--wcpt-property-list-grid-border-style">
          <option value="" selected>Auto</option>
          <option value="solid">Solid</option>
          <option value="dashed">Dashed</option>
          <option value="dotted">Dotted</option>
        </select>
        <input type="text" wcpt-model-key="--wcpt-property-list-grid-border-color" class="wcpt-color-picker"
          placeholder="color">
      </div>
    </div>

    <!-- border radius -->
    <div class="wcpt-editor-row-option">
      <label>Border radius</label>
      <input type="text" wcpt-model-key="--wcpt-property-list-grid-border-radius" placeholder="6px">
    </div>

    <!-- background color -->
    <div class="wcpt-editor-row-option">
      <label>Background color</label>
      <input type="text" wcpt-model-key="--wcpt-property-list-grid-background-color" class="wcpt-color-picker">
    </div>

    <!-- container width -->
    <div class="wcpt-editor-row-option">
      <label>Container width</label>
      <input type="text" wcpt-model-key="width" placeholder="auto">
    </div>

    <?php echo str_replace('{layout}', 'grid', $property_name_value_style_options); ?>

  </div>
</div>

<!-- table layout -->
<div class="wcpt-editor-row-option" wcpt-model-key="style" wcpt-panel-condition="prop" wcpt-condition-prop="layout"
  wcpt-condition-val="table">
  <div class="wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="[id].wcpt-property-list--table-layout">
    <span class="wcpt-toggle-label">
      <?php echo wcpt_icon('paint-brush'); ?>
      Style for Table Layout
      <?php echo wcpt_icon('chevron-down'); ?>
    </span>

    <!-- odd row background color -->
    <div class="wcpt-editor-row-option">
      <label>Odd row background color</label>
      <input type="text" wcpt-model-key="--wcpt-property-list-table-odd-row-background-color" class="wcpt-color-picker">
    </div>

    <!-- even row background color -->
    <div class="wcpt-editor-row-option">
      <label>Even row background color</label>
      <input type="text" wcpt-model-key="--wcpt-property-list-table-even-row-background-color"
        class="wcpt-color-picker">
    </div>

    <!-- label column width -->
    <div class="wcpt-editor-row-option">
      <label>Label column width</label>
      <input type="text" wcpt-model-key="--wcpt-property-list-table-label-column-width">
    </div>

    <!-- gap above -->
    <div class="wcpt-editor-row-option">
      <label>Gap above</label>
      <input type="text" wcpt-model-key="--wcpt-property-list-table-gap-above">
    </div>

    <!-- gap below -->
    <div class="wcpt-editor-row-option">
      <label>Gap below</label>
      <input type="text" wcpt-model-key="--wcpt-property-list-table-gap-below">
    </div>

    <!-- inner border -->
    <div class="wcpt-editor-row-option">
      <label>Inner borders</label>
      <div class="wcpt-flex-option-container">
        <input type="text" wcpt-model-key="--wcpt-property-list-table-inner-border-width" placeholder="thickness (px)">
        <select wcpt-model-key="--wcpt-property-list-table-inner-border-style">
          <option value="" selected>Auto</option>
          <option value="solid">Solid</option>
          <option value="dashed">Dashed</option>
          <option value="dotted">Dotted</option>
        </select>
        <input type="text" wcpt-model-key="--wcpt-property-list-table-inner-border-color" class="wcpt-color-picker"
          placeholder="color">
      </div>
    </div>

    <!-- border -->
    <div class="wcpt-editor-row-option">
      <label>Border</label>
      <div class="wcpt-flex-option-container">
        <input type="text" wcpt-model-key="--wcpt-property-list-table-border-width" placeholder="thickness (px)">
        <select wcpt-model-key="--wcpt-property-list-table-border-style">
          <option value="" selected>Auto</option>
          <option value="solid">Solid</option>
          <option value="dashed">Dashed</option>
          <option value="dotted">Dotted</option>
        </select>
        <input type="text" wcpt-model-key="--wcpt-property-list-table-border-color" class="wcpt-color-picker"
          placeholder="color">
      </div>
    </div>

    <!-- border radius -->
    <div class="wcpt-editor-row-option">
      <label>Border radius</label>
      <input type="text" wcpt-model-key="--wcpt-property-list-table-border-radius" placeholder="6px">
    </div>

    <!-- padding -->
    <div class="wcpt-editor-row-option">
      <label>Cell padding</label>
      <div class="wcpt-flex-option-container">
        <input type="text" wcpt-model-key="--wcpt-property-list-table-cell-padding-vertical"
          placeholder="vertical (6px)">
        <input type="text" wcpt-model-key="--wcpt-property-list-table-cell-padding-horizontal"
          placeholder="horizontal (8px)">
      </div>
    </div>

    <!-- container width -->
    <div class="wcpt-editor-row-option">
      <label>Container width</label>
      <input type="text" wcpt-model-key="width" placeholder="auto">
    </div>

    <?php echo str_replace('{layout}', 'table', $property_name_value_style_options); ?>

  </div>
</div>


<!-- bar layout -->
<div class="wcpt-editor-row-option" wcpt-model-key="style" wcpt-panel-condition="prop" wcpt-condition-prop="layout"
  wcpt-condition-val="bar">
  <div class="wcpt-editor-row-option wcpt-toggle-options wcpt-row-accordion"
    wcpt-model-key="[id].wcpt-property-list--bar-layout">
    <span class="wcpt-toggle-label">
      <?php echo wcpt_icon('paint-brush'); ?>
      Style for Bar Layout
      <?php echo wcpt_icon('chevron-down'); ?>
    </span>

    <!-- gap between property name and value -->
    <div class="wcpt-editor-row-option">
      <label>Gap between property name and value</label>
      <input type="text" wcpt-model-key="--wcpt-property-list-bar-gap-between-name-and-value" placeholder="10px">
    </div>

    <!-- horizontal gap between properties -->
    <div class="wcpt-editor-row-option">
      <label>Horizontal gap between properties</label>
      <input type="text" wcpt-model-key="--wcpt-property-list-bar-gap-between-properties-horizontal" placeholder="30px">
    </div>

    <!-- vertical gap between properties -->
    <div class="wcpt-editor-row-option">
      <label>Vertical gap between properties</label>
      <input type="text" wcpt-model-key="--wcpt-property-list-bar-gap-between-properties-vertical" placeholder="10px">
    </div>

    <!-- property separator (vertical line between properties) -->
    <div class="wcpt-editor-row-option">
      <label>Property separator (vertical line between properties)</label>
      <div class="wcpt-flex-option-container">
        <input type="text" wcpt-model-key="--wcpt-property-list-bar-separator-width" placeholder="thickness (px)">
        <input type="text" min="10" max="100" wcpt-model-key="--wcpt-property-list-bar-separator-height"
          placeholder="height (default:80%)">
        <input type="text" class="wcpt-color-picker" wcpt-model-key="--wcpt-property-list-bar-separator-color"
          placeholder="color">
      </div>
    </div>

    <!-- padding -->
    <div class="wcpt-editor-row-option">
      <label>Padding</label>
      <div class="wcpt-flex-option-container">
        <input type="text" wcpt-model-key="--wcpt-property-list-bar-padding-top" placeholder="top">
        <input type="text" wcpt-model-key="--wcpt-property-list-bar-padding-right" placeholder="right">
        <input type="text" wcpt-model-key="--wcpt-property-list-bar-padding-bottom" placeholder="bottom">
        <input type="text" wcpt-model-key="--wcpt-property-list-bar-padding-left" placeholder="left">
      </div>
    </div>

    <!-- gap above -->
    <div class="wcpt-editor-row-option">
      <label>Gap above</label>
      <input type="text" wcpt-model-key="--wcpt-property-list-bar-gap-above">
    </div>

    <!-- gap below -->
    <div class="wcpt-editor-row-option">
      <label>Gap below</label>
      <input type="text" wcpt-model-key="--wcpt-property-list-bar-gap-below">
    </div>

    <!-- border -->
    <div class="wcpt-editor-row-option">
      <label>Border</label>
      <div class="wcpt-flex-option-container">
        <input type="text" wcpt-model-key="--wcpt-property-list-bar-border-width" placeholder="thickness (px)">
        <select wcpt-model-key="--wcpt-property-list-bar-border-style">
          <option value="" selected>Auto</option>
          <option value="solid">Solid</option>
          <option value="dashed">Dashed</option>
          <option value="dotted">Dotted</option>
        </select>
        <input type="text" wcpt-model-key="--wcpt-property-list-bar-border-color" class="wcpt-color-picker"
          placeholder="color">
      </div>
    </div>

    <!-- border radius -->
    <div class="wcpt-editor-row-option">
      <label>Border radius</label>
      <input type="text" wcpt-model-key="--wcpt-property-list-bar-border-radius" placeholder="6px">
    </div>

    <!-- background color -->
    <div class="wcpt-editor-row-option">
      <label>Background color</label>
      <input type="text" wcpt-model-key="--wcpt-property-list-bar-background-color" class="wcpt-color-picker">
    </div>

    <!-- container width -->
    <div class="wcpt-editor-row-option">
      <label>Container width</label>
      <input type="text" wcpt-model-key="width" placeholder="auto">
    </div>

    <?php echo str_replace('{layout}', 'bar', $property_name_value_style_options); ?>

  </div>
</div>

<!-- justified layout -->
<div class="wcpt-editor-row-option" wcpt-model-key="style" wcpt-panel-condition="prop" wcpt-condition-prop="layout"
  wcpt-condition-val="justified">
  <div class="wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="[id].wcpt-property-list--justified-layout">
    <span class="wcpt-toggle-label">
      <?php echo wcpt_icon('paint-brush'); ?>
      Style for Justified Layout
      <?php echo wcpt_icon('chevron-down'); ?>
    </span>

    <!-- dot separator color -->
    <div class="wcpt-editor-row-option">
      <label>Dot separator color</label>
      <input type="text" class="wcpt-color-picker" wcpt-model-key="--wcpt-property-list-justified-dot-separator-color"
        placeholder="color">
    </div>

    <!-- gap between property rows -->
    <div class="wcpt-editor-row-option">
      <label>Gap between property rows</label>
      <input type="text" wcpt-model-key="--wcpt-property-list-justified-gap-between-rows" placeholder="10px">
    </div>

    <!-- gap between columns -->
    <div class="wcpt-editor-row-option">
      <label>Gap between columns</label>
      <input type="text" wcpt-model-key="--wcpt-property-list-justified-gap-between-columns" placeholder="10px">
    </div>

    <!-- column separator -->
    <div class="wcpt-editor-row-option">
      <label>Column separator</label>
      <div class="wcpt-flex-option-container">
        <input type="text" wcpt-model-key="--wcpt-property-list-justified-separator-width" placeholder="thickness (px)">
        <input type="text" min="10" max="100" wcpt-model-key="--wcpt-property-list-justified-separator-height"
          placeholder="height (default:80%)">
        <input type="text" class="wcpt-color-picker" wcpt-model-key="--wcpt-property-list-justified-separator-color"
          placeholder="color">
      </div>
    </div>

    <!-- padding -->
    <div class="wcpt-editor-row-option">
      <label>Padding</label>
      <div class="wcpt-flex-option-container">
        <input type="text" wcpt-model-key="--wcpt-property-list-justified-padding-top" placeholder="top">
        <input type="text" wcpt-model-key="--wcpt-property-list-justified-padding-right" placeholder="right">
        <input type="text" wcpt-model-key="--wcpt-property-list-justified-padding-bottom" placeholder="bottom">
        <input type="text" wcpt-model-key="--wcpt-property-list-justified-padding-left" placeholder="left">
      </div>
    </div>

    <!-- gap above -->
    <div class="wcpt-editor-row-option">
      <label>Gap above</label>
      <input type="text" wcpt-model-key="--wcpt-property-list-justified-gap-above">
    </div>

    <!-- gap below -->
    <div class="wcpt-editor-row-option">
      <label>Gap below</label>
      <input type="text" wcpt-model-key="--wcpt-property-list-justified-gap-below">
    </div>

    <!-- border -->
    <div class="wcpt-editor-row-option">
      <label>Border</label>
      <div class="wcpt-flex-option-container">
        <input type="text" wcpt-model-key="--wcpt-property-list-justified-border-width" placeholder="thickness (px)">
        <select wcpt-model-key="--wcpt-property-list-justified-border-style">
          <option value="" selected>Auto</option>
          <option value="solid">Solid</option>
          <option value="dashed">Dashed</option>
          <option value="dotted">Dotted</option>
        </select>
        <input type="text" wcpt-model-key="--wcpt-property-list-justified-border-color" class="wcpt-color-picker"
          placeholder="color">
      </div>
    </div>

    <!-- border radius -->
    <div class="wcpt-editor-row-option">
      <label>Border radius</label>
      <input type="text" wcpt-model-key="--wcpt-property-list-justified-border-radius" placeholder="6px">
    </div>

    <!-- background color -->
    <div class="wcpt-editor-row-option">
      <label>Background color</label>
      <input type="text" wcpt-model-key="--wcpt-property-list-justified-background-color" class="wcpt-color-picker">
    </div>

    <!-- container width -->
    <div class="wcpt-editor-row-option">
      <label>Container width</label>
      <input type="text" wcpt-model-key="width" placeholder="auto">
    </div>

    <?php echo str_replace('{layout}', 'justified', $property_name_value_style_options); ?>

  </div>
</div>

<!-- common styles -->
<div class="wcpt-editor-row-option" wcpt-model-key="style">

  <!-- trigger text -->
  <div class="wcpt-editor-row-option">
    <div class="wcpt-toggle-options wcpt-row-accordion">

      <span class="wcpt-toggle-label">
        <?php echo wcpt_icon('paint-brush'); ?>
        Style for 'Show more/less' Button
        <?php echo wcpt_icon('chevron-down'); ?>
      </span>

      <div wcpt-model-key="[id] .wcpt-tg-trigger">
        <!-- font-size -->
        <div class="wcpt-editor-row-option">
          <label>Font size</label>
          <input type="text" wcpt-model-key="--wcpt-property-list-toggle-button-font-size" />
        </div>

        <!-- font color -->
        <div class="wcpt-editor-row-option">
          <label>Font color</label>
          <input type="text" wcpt-model-key="--wcpt-property-list-toggle-button-text-color" placeholder="#000"
            class="wcpt-color-picker">
        </div>

        <!-- font-weight -->
        <div class="wcpt-editor-row-option">
          <label>Font weight</label>
          <select wcpt-model-key="--wcpt-property-list-toggle-button-font-weight">
            <option value="">Auto</option>
            <option value="normal">Normal</option>
            <option value="bold">Bold</option>
            <option value="200">Light</option>
          </select>
        </div>

        <!-- gap above -->
        <div class="wcpt-editor-row-option">
          <label>Gap from properties above</label>
          <input type="text" wcpt-model-key="--wcpt-property-list-toggle-button-gap-above"
            style="width: 100% !important;">
        </div>
      </div>

      <!-- trigger icon -->
      <div class="wcpt-editor-row-option">
        <div class="wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="[id] .wcpt-tg-trigger .wcpt-icon">

          <span class="wcpt-toggle-label">
            <?php echo wcpt_icon('paint-brush'); ?>
            Style for Icon
            <?php echo wcpt_icon('chevron-down'); ?>
          </span>

          <!-- font-size -->
          <div class="wcpt-editor-row-option">
            <label>Size</label>
            <input type="text" wcpt-model-key="--wcpt-property-list-toggle-button-icon-size">
          </div>

          <!-- font-color -->
          <div class="wcpt-editor-row-option">
            <label>Stroke color</label>
            <input type="text" wcpt-model-key="--wcpt-property-list-toggle-button-icon-color" placeholder="#000"
              class="wcpt-color-picker">
          </div>

          <!-- stroke-width -->
          <div class="wcpt-editor-row-option">
            <label>Thickness</label>
            <input type="text" wcpt-model-key="--wcpt-property-list-toggle-button-icon-stroke-thickness">
          </div>

        </div>
      </div>

    </div>
  </div>

</div>