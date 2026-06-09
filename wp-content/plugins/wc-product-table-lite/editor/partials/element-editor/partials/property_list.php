<div class="wcpt-editor-row-option">
  <label class="wcpt-editor-options-heading">Properties to show in the grid
  </label>
</div>

<!-- rows -->
<div class="wcpt-sortable wcpt-editor-row-option" wcpt-model-key="rows">
  <div class="wcpt-editor-row wcpt-editor-custom-label-setup" wcpt-controller="property_list_row" wcpt-model-key="[]"
    wcpt-model-key-index="0" wcpt-row-template="property_list_row" wcpt-initial-data="property_list_row">

    <div class="wcpt-tabs">

      <!-- triggers -->
      <div class="wcpt-tab-triggers">
        <div class="wcpt-tab-trigger">
          Template
        </div>
        <div class="wcpt-tab-trigger">
          Condition
        </div>
      </div>

      <!-- content: template -->
      <div class="wcpt-tab-content">

        <div class="wcpt-editor-row-option">
          <label>Property name</label>
          <div wcpt-model-key="property_name" wcpt-block-editor="" wcpt-be-add-row="0"></div>
        </div>

        <div class="wcpt-editor-row-option">
          <label>Property value</label>
          <div wcpt-model-key="property_value" wcpt-block-editor="" wcpt-be-add-row="0"
            wcpt-be-add-element-partial="add-property-value-element"></div>
        </div>

      </div>

      <!-- content: condition -->
      <div class="wcpt-tab-content" wcpt-model-key="condition">
        <?php include('condition/inner.php'); ?>
      </div>

    </div>

    <!-- corner options -->
    <?php wcpt_corner_options(); ?>

  </div>

  <button class="wcpt-button" wcpt-add-row-template="property_list_row">
    Add a Property
  </button>

</div>

<!-- property list common editor -->
<?php include('_property_list_common.php'); ?>

<!-- condition -->
<?php include('condition/outer.php'); ?>