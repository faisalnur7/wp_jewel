<!-- file source -->
<div class="wcpt-editor-row-option">
  <label>
    Video file source
  </label>
  <select wcpt-model-key="source" style="width: 100%;">
    <option value="content">Product content</option>
    <option value="short_description">Short description</option>
    <option value="custom_field_url">Custom field with file URL</option>
    <option value="custom_field_media_id">Custom field with media ID</option>
  </select>
</div>

<!-- custom field name -->
<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="source"
  wcpt-condition-val="custom_field_url||custom_field_media_id">
  <label>
    Custom field name
  </label>
  <input type="text" wcpt-model-key="custom_field_name" />
</div>

<!-- loop -->
<div class="wcpt-editor-row-option">
  <label>
    <input type="checkbox" wcpt-model-key="loop" />
    Loop video infinitely
  </label>
</div>

<!-- condition -->
<?php include('condition/outer.php'); ?>