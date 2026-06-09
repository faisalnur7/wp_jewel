<!-- heading -->
<div class="wcpt-editor-row-option">
  <label>
    Heading
  </label>
  <div wcpt-block-editor wcpt-be-add-element-partial="add-navigation-filter-heading-element" wcpt-model-key="heading"
    wcpt-be-add-row="0"></div>
</div>

<!-- display type -->
<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="position"
  wcpt-condition-val="header">
  <label>Display type</label>
  <select wcpt-model-key="display_type">
    <option value="dropdown">Dropdown</option>
    <option value="row">Row of buttons</option>
  </select>
</div>

<!-- operator -->
<div class="wcpt-editor-row-option">
  <label>Operator</label>
  <select wcpt-model-key="operator">
    <option value="IN">"IN" - each result must have at least one selected term</option>
    <option value="AND">"AND" - each result must have all the selected terms</option>
    <option value="NOT IN">"NOT IN" - each result must have none of the selected terms</option>
  </select>
</div>

<!-- multiple selections permission -->
<div class="wcpt-editor-row-option">
  <label>
    <input type="checkbox" wcpt-model-key="single" />
    Only allow one option to be selected
  </label>
</div>

<!-- heading format upon option selection -->
<?php require('heading_format__op_selected.php'); ?>

<!-- "Show all" label -->
<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="single" wcpt-condition-val="true">
  <label>
    "Show All" option label
  </label>
  <div wcpt-model-key="show_all_label" wcpt-block-editor wcpt-be-add-row="0"></div>
</div>

<!-- exclude terms -->
<div class="wcpt-editor-row-option">
  <label>
    Exclude brands by slug
    <small>Enter one brand slug <em>per line</em></small>
  </label>
  <textarea wcpt-model-key="exclude_terms"></textarea>
</div>

<!-- hide empty -->
<div class="wcpt-editor-row-option">
  <label>
    <input type="checkbox" wcpt-model-key="hide_empty"> Hide empty brands (not attached to any product on the site)
  </label>
</div>

<!-- accordion always open -->
<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="position"
  wcpt-condition-val="left_sidebar">
  <label>
    <input type="checkbox" wcpt-model-key="accordion_always_open"> Keep filter open by default in sidebar
  </label>
</div>

<!-- pre-open depth -->
<div class="wcpt-editor-row-option">
  <label>
    Pre-open filter dropdown till depth
  </label>
  <input type="number" wcpt-model-key="pre_open_depth" min="0">
</div>

<!-- enable search -->
<div class="wcpt-editor-row-option">
  <?php wcpt_pro_checkbox('true', 'Enable search box for the filter options', 'search_enabled'); ?>
</div>

<!-- search placeholder -->
<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="search_enabled"
  wcpt-condition-val="true">
  <label>Placeholder for the search input box</label>
  <input type="text" wcpt-model-key="search_placeholder">
</div>

<?php include('style/filter.php'); ?>