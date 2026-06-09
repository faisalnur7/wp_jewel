<?php if (empty($only_single_allowed)): ?>
  <!-- number of attributes -->
  <div class="wcpt-editor-row-option">
    <label>
      Number of attributes to show
    </label>
    <label>
      <input type="radio" wcpt-model-key="number_of_attributes" value="single">
      Single attribute
    </label>
    <label>
      <input type="radio" wcpt-model-key="number_of_attributes" value="multiple">
      Multiple attributes
    </label>
  </div>


  <!-- multiple attribute options -->
  <div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="number_of_attributes"
    wcpt-condition-val="multiple">

    <!-- number of attributes -->
    <div class="wcpt-editor-row-option">
      <label>
        Which attributes to show
      </label>
      <label>
        <input type="radio" wcpt-model-key="attribute_criteria" value="all">
        All attributes of the product
      </label>
      <label>
        <input type="radio" wcpt-model-key="attribute_criteria" value="custom">
        Custom set of attributes only
      </label>
    </div>

    <!-- exclude variation attributes -->
    <div class="wcpt-editor-row-option">
      <label>
        <input type="checkbox" wcpt-model-key="exclude_variation_attributes">
        Exclude attributes used for variations
      </label>
    </div>

    <!-- exclude attributes by slug -->
    <div class="wcpt-editor-row-option">
      <label>
        Exclude attributes by slug
        <small>Enter one attribute slug per line</small>
      </label>
      <textarea wcpt-model-key="exclude_attributes"></textarea>
    </div>

    <!-- max attributes -->
    <div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="attribute_criteria"
      wcpt-condition-val="all">
      <label>
        Maximum number of attributes to show
      </label>
      <input type="number" wcpt-model-key="max_attributes" min="1" placeholder="Leave empty to show all" />
    </div>

    <div class="wcpt-editor-row-option" style="border-bottom: 1px solid #ddd;
    margin-bottom: 15px; padding-bottom: 0px;">
      <label wcpt-panel-condition="prop" wcpt-condition-prop="attribute_criteria" wcpt-condition-val="all">
        Attribute relabel rules
        <?php wcpt_editor_tooltip('You can relabel attribute names here if you wish. You can also set the order of the attributes.'); ?>
      </label>
      <label wcpt-panel-condition="prop" wcpt-condition-prop="attribute_criteria" wcpt-condition-val="custom">
        Select attributes to show
        <?php wcpt_editor_tooltip('Select the attributes you want to show in the table for each product. You can also set the order of the attributes.'); ?>
      </label>
    </div>

    <!-- attributes -->
    <div class="wcpt-label-options-rows-wrapper wcpt-sortable" wcpt-model-key="attributes">
      <div class="wcpt-editor-row wcpt-editor-custom-label-setup" wcpt-model-key="[]" wcpt-model-key-index="0"
        wcpt-initial-data="attribute_option" wcpt-controller="attribute_option" wcpt-row-template="attribute_option">

        <!-- attribute -->
        <div class="wcpt-editor-row-option" style="padding-top: 0;">
          <label>Attribute name</label>
          <select wcpt-model-key="attribute_name">
            <option value="">Select an attribute</option>
            <?php
            foreach (wc_get_attribute_taxonomies() as $attribute) {
              ?>
              <option value="<?php echo $attribute->attribute_name; ?>">
                <?php echo $attribute->attribute_label; ?>
              </option>
              <?php
            }
            ?>
          </select>
        </div>

        <!-- property label -->
        <?php
        $is_for_attribute = true;
        include('property_label.php');
        ?>

        <!-- corner options -->
        <?php wcpt_corner_options(); ?>


      </div>

      <button class="wcpt-button" wcpt-add-row-template="attribute_option">
        + Add an Attribute
      </button>

    </div>
  </div>
<?php endif; ?>

<!-- single attribute options -->
<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="number_of_attributes"
  wcpt-condition-val="single">

  <!-- attribute type -->
  <div class="wcpt-editor-row-option">
    <label>
      Attribute type
      <small>
        Note: Global attributes are set at store level and used for filtering
        (<a href="https://woocommerce.com/document/managing-product-taxonomies/#product-attributes" target="_blank">see
          doc</a>). Custom attribute are set at product level.
      </small>
    </label>
    <select wcpt-model-key="attribute_type">
      <option value="global">Global attribute</option>
      <option value="custom">Custom attribute</option>
    </select>
  </div>

  <div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="attribute_type"
    wcpt-condition-val="custom">
    <!-- custom attribute name -->
    <div class="wcpt-editor-row-option">
      <label>
        Custom attribute name
      </label>
      <input type="text" wcpt-model-key="custom_attribute_name">
    </div>

  </div>

  <!-- select global attribute -->
  <div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="attribute_type"
    wcpt-condition-val="global">
    <label>
      Select global attribute
    </label>

    <?php
    $attributes = wc_get_attribute_taxonomies();
    if (empty($attributes)) {
      echo '<div class="wcpt-notice">There are no WooCommerce attributes on this site!</div>';
      $hide_class = 'wcpt-hide';
    }
    ?>
    <select class="<?php echo empty($attributes) ? 'wcpt-hide' : ''; ?>" wcpt-model-key="attribute_name">
      <option value="">Attribute list</option>
      <?php
      foreach ($attributes as $attribute) {
        ?>
        <option value="<?php echo $attribute->attribute_name; ?>">
          <?php echo $attribute->attribute_label; ?>
        </option>
        <?php
      }
      ?>
    </select>
  </div>

  <?php if (empty($only_single_allowed)): ?>
    <!-- property label -->
    <?php include('property_label.php'); ?>
  <?php endif; ?>
</div>

<!-- link term to filter -->
<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="attribute_type"
  wcpt-condition-val="global">
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

<!-- terms in separate lines -->
<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="number_of_attributes"
  wcpt-condition-val="single">
  <label>
    <input type="checkbox" wcpt-model-key="separate_lines">
    Show multiple terms in separate lines
  </label>
</div>

<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="number_of_attributes"
  wcpt-condition-val="single">
  <div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="separate_lines"
    wcpt-condition-val="false">
    <!-- term separator -->
    <div class="wcpt-editor-row-option">
      <label>Separator between multiple terms</label>
      <div wcpt-model-key="separator" class="wcpt-separator-editor" wcpt-block-editor="" wcpt-be-add-row="0"></div>
    </div>
  </div>
</div>

<!-- empty value relabel -->
<div class="wcpt-editor-row-option">
  <label>Output when no terms</label>
  <div wcpt-model-key="empty_relabel" wcpt-block-editor="" wcpt-be-add-row="0"></div>
</div>

<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="attribute_type"
  wcpt-condition-val="global">

  <!-- exclude terms -->
  <div class="wcpt-editor-row-option">
    <label>
      Exclude terms by slug
      <small>Enter one term slug <em>per line</em></small>
    </label>
    <textarea wcpt-model-key="exclude_terms"></textarea>
  </div>

  <!-- relabel -->
  <div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="number_of_attributes"
    wcpt-condition-val="single">
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
        <div class="wcpt-editor-custom-label-setup" wcpt-controller="relabels" wcpt-model-key="[]"
          wcpt-model-key-index="0" wcpt-row-template="relabel_rule_term_column_element_2">


          <div class="wcpt-tabs">

            <!-- triggers -->
            <div class="wcpt-tab-triggers">
              <div class="wcpt-tab-trigger" wcpt-content-template="term">
                Term name
              </div>
              <div class="wcpt-tab-trigger">
                Style
              </div>
            </div>

            <!-- content: term label -->
            <div class="wcpt-tab-content">
              <div class="wcpt-editor-row-option">
                <div wcpt-model-key="label" class="wcpt-term-relabel-editor" wcpt-block-editor="" wcpt-be-add-row="0"
                  wcpt-be-add-element-partial="add-term-element-col"></div>
              </div>
            </div>

            <!-- content: term style -->
            <div class="wcpt-tab-content">

              <div class="wcpt-editor-row-option" wcpt-model-key="style">
                <div class="wcpt-editor-row-option" wcpt-model-key="[id]">

                  <!-- font color -->
                  <div class="wcpt-editor-row-option">
                    <label>Font color</label>
                    <input type="text" wcpt-model-key="color" placeholder="#000" class="wcpt-color-picker">
                  </div>

                  <!-- background color -->
                  <div class="wcpt-editor-row-option">
                    <label>Background color</label>
                    <input type="text" wcpt-model-key="background-color" class="wcpt-color-picker">
                  </div>

                  <!-- border color -->
                  <div class="wcpt-editor-row-option">
                    <label>Border color</label>
                    <input type="text" wcpt-model-key="border-color" class="wcpt-color-picker">
                  </div>

                </div>
              </div>

            </div>

          </div>

        </div>

      </div>

    </div>
  </div>

</div>

<?php wcpt_editor_more_options_container_end(); ?>

<?php if (empty($only_single_allowed)): ?>
  <div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="number_of_attributes"
    wcpt-condition-val="multiple" wcpt-controller="property_list_options" wcpt-model-key="property_list_options">
    <?php include('_property_list_common.php'); ?>
  </div>
<?php endif; ?>

<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="number_of_attributes"
  wcpt-condition-val="single">
  <!-- style -->
  <?php include('style/parent-child.php'); ?>
</div>

<!-- condition -->
<?php include('condition/outer.php'); ?>