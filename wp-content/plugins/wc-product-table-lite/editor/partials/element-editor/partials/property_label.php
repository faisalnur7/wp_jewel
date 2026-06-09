<?php if (empty($is_for_attribute)): ?>
  <!-- terms in separate lines -->
  <div class="wcpt-editor-row-option">
    <label>
      <input type="checkbox" wcpt-model-key="enable_property_label">
      Show a label (text & icon) before the property value
    </label>
  </div>
<?php endif; ?>

<!-- property label text and icon -->
<?php if (empty($is_for_attribute)): ?>
  <div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="enable_property_label"
    wcpt-condition-val="true" style="border-left: 1px solid #ddd;
    padding-left: 25px;">
  <?php endif; ?>

  <!-- property label text -->
  <div class="wcpt-editor-row-option">
    <label>
      <?php if (empty($is_for_attribute)): ?>
        Property label text
      <?php else: ?>
        Attribute label text
      <?php endif; ?>
    </label>
    <input type="text" wcpt-model-key="property_label_text" <?php echo empty($is_for_attribute) ? '' : 'placeholder="[attribute_name]"'; ?>>
  </div>

  <!-- property label icon source -->
  <div class="wcpt-editor-row-option">
    <label>
      <?php if (empty($is_for_attribute)): ?>
        Property label icon source <?php wcpt_pro_badge(); ?>
      <?php else: ?>
        Attribute label icon source <?php wcpt_pro_badge(); ?>
      <?php endif; ?>
    </label>
    <select wcpt-model-key="property_label_icon_source">
      <option value="included">Included icons</option>
      <option value="custom">Custom SVG icon</option>
    </select>
  </div>

  <div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="property_label_icon_source"
    wcpt-condition-val="included">
    <label>
      <?php if (empty($is_for_attribute)): ?>
        Select property label icon <?php wcpt_pro_badge(); ?>
      <?php else: ?>
        Select attribute label icon <?php wcpt_pro_badge(); ?>
      <?php endif; ?>
    </label>
    <?php wcpt_print_icon_dopdown('property_label_icon_name'); ?>
  </div>

  <div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="property_label_icon_source"
    wcpt-condition-val="custom">
    <label>
      <?php if (empty($is_for_attribute)): ?>
        Custom SVG icon <?php wcpt_pro_badge(); ?>
      <?php else: ?>
        Custom SVG attribute icon <?php wcpt_pro_badge(); ?>
      <?php endif; ?>
      <small>Copy SVG code from <a href="https://tablericons.com/" target="_blank">Tabler Icon</a> and paste it here
      </small>
    </label>
    <textarea wcpt-model-key="property_label_custom_icon"></textarea>
  </div>
  <?php if (empty($is_for_attribute)): ?>
  </div>
<?php endif;
  $is_for_attribute = false;
  ?>