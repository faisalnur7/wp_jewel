<!-- property label -->
<?php include('property_label.php'); ?>

<!-- link term to filter -->
<div class="wcpt-editor-row-option">
  <label>
    Action on click:
  </label>
  <label><input type="radio" wcpt-model-key="click_action" value="">Do nothing</label>
  <?php wcpt_pro_radio('archive_redirect', 'Go to archive page', 'click_action'); ?>
  <?php wcpt_pro_radio('trigger_filter', 'Trigger matching filter', 'click_action'); ?>
  <label wcpt-panel-condition="prop" wcpt-condition-prop="click_action" wcpt-condition-val="trigger_filter">
    <small>
      Note: This option requires you to have the corresponding navigation filter element setup in your table
      navigation section.
    </small>
  </label>
</div>

<?php wcpt_editor_more_options_container_start(); ?>

<!-- term separator -->
<div class="wcpt-editor-row-option">
  <label>Separator between multiple brands</label>
  <div wcpt-model-key="separator" class="wcpt-separator-editor" wcpt-block-editor="" wcpt-be-add-row="0"></div>
</div>

<!-- empty value relabel -->
<div class="wcpt-editor-row-option">
  <label>Output when no brand is found</label>
  <div wcpt-model-key="empty_relabel" wcpt-block-editor="" wcpt-be-add-row="0"></div>
</div>

<!-- exclude terms -->
<div class="wcpt-editor-row-option">
  <label>
    Exclude brands by slug
    <small>Enter one brand slug <em>per line</em></small>
  </label>
  <textarea wcpt-model-key="exclude_terms"></textarea>
</div>

<?php wcpt_editor_more_options_container_end(); ?>

<?php include('style/parent-child.php'); ?>

<!-- condition -->
<?php include('condition/outer.php'); ?>