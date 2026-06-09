<div class="wcpt-column-settings wcpt-toggle-column-expand" wcpt-controller="column_settings" wcpt-model-key="[]"
  wcpt-model-key-index="0" wcpt-row-template="column_settings_<?php echo $device; ?>"
  wcpt-initial-data="column_settings">

  <!-- <div class="wcpt-column-toggle-capture"></div> -->

  <?php ob_start(); ?>
  <i class="wcpt-editor-row-expand" wcpt-expand title="Expand">
    <?php wcpt_icon('maximize-2'); ?>
  </i>
  <i class="wcpt-editor-row-contract" wcpt-expand title="Contract">
    <?php wcpt_icon('minimize-2'); ?>
  </i>
  <?php wcpt_corner_options(array('prepend' => ob_get_clean())); ?>

  <!-- column settings index -->
  <span class="wcpt-column-settings__index">Laptop column #1/5</span>

  <!-- column index -->
  <div class="wcpt-column-setttings__top-left">
    <span class="wcpt-column-name-label">Column name</span>
    <input type="text" class="wcpt-column-name-input" data-wcpt-diw-disabled="true"
      placeholder="Column # - reference name for this column in the editor" autocomplete="off" wcpt-model-key="name" />

    <span class="wcpt-tooltip" style="position: absolute; right: 10px;" data-wcpt-direction="bottom">
      <span class="wcpt-tooltip-icon"><?php wcpt_icon('help-circle'); ?></span>
      <span class="wcpt-tooltip-content">Create a reference name for the column in the editor. The column
        buttons above will use the same name that you enter here.</span>
    </span>
  </div>

  <!-- attribute column settings -->
  <div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="type"
    wcpt-condition-val="attribute_column_generator" wcpt-model-key="generator_settings" style="margin-top: 20px;padding: 20px;
    background: white;
    border: 1px solid #e0e0e0;">
    <?php require_once 'attribute-column-generator.php'; ?>
  </div>

  <!-- normal column settings -->
  <div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="type"
    wcpt-condition-val="!attribute_column_generator">

    <!-- heading -->
    <div class="wcpt-tabs" wcpt-model-key="heading" style="margin-bottom: 20px;">
      <div class="wcpt-tab-triggers">
        <div class="wcpt-tab-trigger wcpt-tab-trigger--heading-content">
          <?php wcpt_icon('file-text'); ?>
          Column heading
        </div>
        <div class="wcpt-tab-trigger wcpt-tab-trigger--heading-style">
          <?php wcpt_icon('paint-brush'); ?>
          Style
        </div>
        <span class="wcpt-tooltip" data-wcpt-direction="bottom" style="margin-left: 10px;">
          <span class="wcpt-tooltip-icon"><?php wcpt_icon('help-circle'); ?></span>
          <span class="wcpt-tooltip-content">Use the '+ Add element' button below to add 'Text', 'Sorting' or 'Tooltip'
            elements in the column heading.</span>
        </span>
      </div>

      <!-- heading editor -->
      <div class="wcpt-tab-content wcpt-tab-content--heading-content">
        <div class="wcpt-block-editor wcpt-column-heading-editor" wcpt-model-key="content"></div>
      </div>

      <!-- design options -->
      <div class="wcpt-tab-content wcpt-tab-content--heading-style">
        <?php include('element-editor/partials/column-heading-style.php'); ?>
      </div>

    </div>

    <!-- template -->
    <div class="wcpt-tabs" wcpt-model-key="cell">
      <div class="wcpt-tab-triggers">
        <div class="wcpt-tab-trigger wcpt-tab-trigger--cell-content">
          <?php wcpt_icon('file-text'); ?>
          Column cell
        </div>
        <div class="wcpt-tab-trigger wcpt-tab-trigger--cell-style">
          <?php wcpt_icon('paint-brush'); ?>
          Style
        </div>

        <span class="wcpt-tooltip" data-wcpt-direction="bottom" style="margin-left: 10px;">
          <span class="wcpt-tooltip-icon"><?php wcpt_icon('help-circle'); ?></span>
          <span class="wcpt-tooltip-content">Use the '+ Add element' button below to add product property and other
            elements to the column cells.</span>
        </span>
      </div>

      <!-- template editor -->
      <div class="wcpt-tab-content wcpt-tab-content--cell-content">
        <div class="wcpt-block-editor wcpt-column-template-editor" wcpt-model-key="template"></div>
      </div>

      <!-- design options -->
      <div class="wcpt-tab-content wcpt-tab-content--cell-style">
        <?php include('element-editor/partials/column-cell-style.php'); ?>
      </div>

    </div>

  </div>

  <!-- add new column adjacent to this column -->
  <div class="wcpt-add-column-adjacent">
    <a href="#" class="wcpt-add-column-before" wcpt-add-row-template="column_settings_<?php echo $device; ?>"
      wcpt-direction="before"> <?php wcpt_icon('arrow-left'); ?> Add column before this</a>
    <a href="#" class="wcpt-add-column-after" wcpt-add-row-template="column_settings_<?php echo $device; ?>"
      wcpt-direction="after"> Add column after this
      <?php wcpt_icon('arrow-right'); ?>
    </a>
  </div>
</div>

<button class="wcpt-button" wcpt-add-row-template="column_settings_<?php echo $device; ?>">
  <span>+ Add a <?php echo $device; ?> column</span>
</button>