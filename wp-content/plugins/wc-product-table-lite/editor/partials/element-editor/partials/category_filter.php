<input type="hidden" wcpt-model-key="taxonomy" value="product_cat" />

<div class="wcpt-editor-row-option">
  <label for="">
    <small>Note: To re-order categories, please refer to the <a
        href="https://www.notion.so/FAQs-f624e13d0d274a08ba176a98d6d79e1f" target="_blank">plugin FAQs</a> → navigation
      → How can I change the order of category / attribute options appearing in the table?
    </small>
  </label>
</div>

<!-- heading -->
<div class="wcpt-editor-row-option">
  <label>Heading</label>
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

<!-- multiple selections permission -->
<div class="wcpt-editor-row-option">
  <label>
    <input type="checkbox" wcpt-model-key="single" />
    Only allow one option to be selected
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

<?php wcpt_editor_more_options_container_start(); ?>

<!-- heading format upon option selection -->
<?php require('heading_format__op_selected.php'); ?>

<!-- multiple selections permission -->
<div class="wcpt-editor-row-option">
  <label>
    <input type="checkbox" wcpt-model-key="redirect_enabled" />
    Enable category redirect
    <small>Note: Redirects visitor to category page on click</small>
  </label>
</div>

<!-- display all categories -->
<div class="wcpt-editor-row-option">
  <label>
    <input type="checkbox" wcpt-model-key="display_all" />
    Always display all categories
    <small>Note: Related to category archive override facility</small>
  </label>
</div>

<!-- "Show all" label -->
<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="single" wcpt-condition-val="true">
  <label>
    "Show All" option label
  </label>
  <div wcpt-model-key="show_all_label" wcpt-block-editor wcpt-be-add-row="0"></div>
</div>

<!-- relabel -->
<div class="wcpt-editor-row-option ">
  <div class="wcpt-toggle-options wcpt-row-accordion">

    <span class="wcpt-toggle-label">
      <?php echo wcpt_icon('file-text'); ?>
      Custom term labels <?php wcpt_pro_badge(); ?>
      <?php echo wcpt_icon('chevron-down'); ?>
    </span>

    <div class="wcpt-editor-loading" data-loading="terms" style="display: none;">
      <?php wcpt_icon('loader', 'wcpt-rotate'); ?> Loading ...
    </div>

    <div class="
      wcpt-editor-row-option
      <?php wcpt_pro_cover(); ?>
    " wcpt-model-key="relabels">
      <div class="wcpt-editor-row wcpt-editor-custom-label-setup" wcpt-controller="relabels" wcpt-model-key="[]"
        wcpt-model-key-index="0" wcpt-row-template="relabel_rule_term_filter_element_2">
        <div class="wcpt-tabs">

          <!-- triggers -->
          <div class="wcpt-tab-triggers">
            <div class="wcpt-tab-trigger" wcpt-content-template="term">
              Term name
            </div>
            <div class="wcpt-tab-trigger" wcpt-can-disable>
              Clear label
            </div>
          </div>

          <!-- content: term label -->
          <div class="wcpt-tab-content">
            <div class="wcpt-editor-row-option">
              <div wcpt-model-key="label" class="wcpt-term-relabel-editor" wcpt-block-editor="" wcpt-be-add-row="0">
              </div>
            </div>
          </div>

          <!-- content: clear fitler label -->
          <div class="wcpt-tab-content">
            <div class="wcpt-editor-row-option">
              <input type="text" wcpt-model-key="clear_label" placeholder="[filter] : [option]">
            </div>
          </div>

        </div>

      </div>

    </div>

  </div>
</div>

<!-- exclude terms -->
<div class="wcpt-editor-row-option">
  <label>
    Exclude category by slug
    <small>Enter one category slug <em>per line</em></small>
  </label>
  <textarea wcpt-model-key="exclude_terms"></textarea>
</div>

<!-- exclude terms -->
<div class="wcpt-editor-row-option">
  <label>
    <input type="checkbox" wcpt-model-key="exclude_children_also">
    Exclude their child categories as well
  </label>
</div>

<!-- hide empty -->
<!-- <div class="wcpt-editor-row-option">
  <label>
    <input type="checkbox" wcpt-model-key="hide_empty"> Hide empty terms (not attached to any product on the site)
  </label>
</div> -->

<?php wcpt_editor_more_options_container_end(); ?>

<?php include('style/filter.php'); ?>