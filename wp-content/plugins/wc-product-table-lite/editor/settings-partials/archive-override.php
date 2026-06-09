<?php
$wcpt_post_query = new WP_Query(
  array(
    'post_type' => 'wc_product_table',
    'posts_per_page' => -1,
  )
);

ob_start();
echo '<option value="">*None* &mdash; show the default woocommerce product grid</option>';
echo '<option value="custom">*Custom shortcode* &mdash; manually enter shortcode</option>';
if ($wcpt_post_query->have_posts()) {
  while ($wcpt_post_query->have_posts()) {
    $wcpt_post_query->the_post();
    $title = get_the_title() ? get_the_title() : '*No name*';
    echo '<option value="' . get_the_ID() . '">' . $title . '</option>';
  }
}
global $wcpt_table_options;
$wcpt_table_options = ob_get_clean();

$wcpt_option_limit = 10000;

function wcpt_custom_shortcode_textarea($condition_prop, $condition_val, $model_key)
{
  ?>
  <div style="margin-top: 5px;" wcpt-panel-condition="prop" wcpt-condition-prop="<?php echo $condition_prop; ?>"
    wcpt-condition-val="<?php echo $condition_val; ?>">
    <textarea class='wcpt-editor-custom-table-shortcode' wcpt-model-key='<?php echo $model_key; ?>'
      placeholder="Enter your [product_table] shortcode here..."></textarea>
  </div>
  <?php
}
?>
<div class="wcpt-toggle-options" wcpt-model-key="archive_override">

  <div class="wcpt-editor-light-heading wcpt-toggle-label">
    Show product table on shop pages
    <?php wcpt_pro_badge(); ?>
    <?php echo wcpt_icon('chevron-down'); ?>
  </div>

  <div class="<?php wcpt_pro_cover(); ?>">
    <!-- override method -->
    <div class="wcpt-editor-row-option">
      <label style="padding-bottom: 0;">Select method to replace product grids on archive pages:</label>
      <div class="wcpt-editor-row-option">
        <label>
          <input type='radio' wcpt-model-key='override_method' value='off'> Off
          <small>Do not replace WooCommerce product grids on archive pages. Your theme’s default shop and category
            layouts are used.</small>
        </label>
        <label>
          <input type='radio' wcpt-model-key='override_method' value='automatic'> Automatic
          <small>Select this option if you're not using any theme builder plugin to modify your product archive page
            template and want a quick and automatic template override.</small>
        </label>
        <label>
          <input type='radio' wcpt-model-key='override_method' value='manual'> Manual (for 3rd party page builders)
          <small>Select this option if:
            <ul style="margin: 5px 0 0 15px !important;list-style: disc !important;">
              <li>You are using a theme builder plugin (eg: Elementor PRO, Divi builder, Bricks, etc) to control your
                product archive page template

                <span class="wcpt-toggle wcpt-toggle-off">
                  <span class="wcpt-toggle-trigger wcpt-noselect"
                    style="padding:3px 4px 3px 6px;border-radius: 4px;border: 1px solid #ddd">
                    Do this
                    <?php wcpt_icon('chevron-down', 'wcpt-toggle-is-off'); ?>
                    <?php wcpt_icon('chevron-up', 'wcpt-toggle-is-on'); ?>
                  </span>

                  <span class="wcpt-toggle-tray" style="font-size: 14px; width: 400px;">
                    Place the shortcode
                    <code>[wcpt_archive_table]</code> in the product archive template you created with the theme
                    builder plugin and remove the default product grid element. For example, if you're using Elementor
                    Pro, you
                    can use the "Shortcode" widget to place the
                    shortcode in the product archive template (<a
                      href="https://wcproducttable.com/documentation/elementor-woocommerce-product-table"
                      target="_blank">see step by step guide</a>). This facility will work for any theme builder plugin.
                  </span>
                </span>

              </li>
              <li>You want to use a custom template override file

                <span class="wcpt-toggle wcpt-toggle-off">
                  <span class="wcpt-toggle-trigger wcpt-noselect"
                    style="padding:3px 4px 3px 6px;border-radius: 4px;border: 1px solid #ddd;">
                    Do this
                    <?php wcpt_icon('chevron-down', 'wcpt-toggle-is-off'); ?>
                    <?php wcpt_icon('chevron-up', 'wcpt-toggle-is-on'); ?>
                  </span>

                  <span class="wcpt-toggle-tray" style="font-size: 14px; width: 400px;">
                    To print the archive table through your custom product archive template add
                    <code>&lt;?php echo do_shortcode( '[wcpt_archive_table]' ); ?&gt;</code>
                    inside it. This will print
                    the correct product table from your archive override settings and show the relevant products based
                    on
                    the archive page.
                  </span>
                </span>


              </li>
            </ul>

          </small>
        </label>
        <div wcpt-panel-condition="prop" wcpt-condition-prop="override_method" wcpt-condition-val="false"
          class="wcpt-notice">↑ Please select one of the above options to proceed.</div>
      </div>
    </div>

    <div wcpt-panel-condition="prop" wcpt-condition-prop="override_method" wcpt-condition-val="automatic||manual">

      <!-- default -->
      <div class="wcpt-editor-row-option">
        <label>Default override table</label>
        <select wcpt-model-key='default'>
          <?php echo $wcpt_table_options; ?>
        </select>
        <?php wcpt_custom_shortcode_textarea('default', 'custom', 'default_custom') ?>
      </div>

      <?php
      // add the default option to table options
      $wcpt_table_options = '<option value="default">Default override table</option>' . $wcpt_table_options;
      ?>

      <!-- shop -->
      <div class="wcpt-editor-row-option">
        <label>Shop override table</label>
        <select wcpt-model-key='shop'>
          <?php echo $wcpt_table_options; ?>
        </select>
        <?php wcpt_custom_shortcode_textarea('shop', 'custom', 'shop_custom') ?>
      </div>

      <!-- search -->
      <div class="wcpt-editor-row-option">
        <label>Search override table</label>
        <select wcpt-model-key='search'>
          <?php echo $wcpt_table_options; ?>
        </select>
        <?php wcpt_custom_shortcode_textarea('search', 'custom', 'search_custom') ?>
      </div>

      <!-- category -->
      <div class="wcpt-toggle-options wcpt-editor-row-option" wcpt-model-key='category'>
        <div class="wcpt-editor-light-heading wcpt-toggle-label">Category override
          <?php echo wcpt_icon('chevron-down'); ?>
        </div>

        <div class="wcpt-editor-row-option">
          <label>Default category override table</label>
          <select wcpt-model-key='default'>
            <?php echo $wcpt_table_options; ?>
          </select>
          <?php wcpt_custom_shortcode_textarea('default', 'custom', 'custom') ?>
        </div>

        <!-- additional category overides -->
        <?php if (wp_count_terms(array('taxonomy' => 'product_cat')) < $wcpt_option_limit): ?>
          <?php
          $wcpt_category_select = wp_dropdown_categories(
            array(
              'echo' => 0,
              'value_field' => 'slug',
              'taxonomy' => 'product_cat',
              'hierarchical' => 1,
              'name' => '',
              'id' => '',
              'class' => '',
            )
          );
          $wcpt_category_select = str_replace('<select ', '<select multiple wcpt-model-key="category" ', $wcpt_category_select);
          ?>

          <div class="wcpt-editor-row-option" wcpt-controller="archive_override_rows" wcpt-model-key="other_rules">
            <!-- row -->
            <div class="wcpt-editor-row-option wcpt-archive-overrider-rule" wcpt-controller="archive_override_row"
              wcpt-model-key="[]" wcpt-model-key-index="0" wcpt-row-template="category-archive-override-rules"
              wcpt-initial-data="">
              <?php wcpt_corner_options(); ?>

              <div class="wcpt-editor-row-option">
                <label>Category</label>
                <?php echo $wcpt_category_select; ?>
              </div>

              <div class="wcpt-editor-row-option">
                <label>Override table</label>
                <select wcpt-model-key='table_id'>
                  <?php echo $wcpt_table_options; ?>
                </select>
                <?php wcpt_custom_shortcode_textarea('table_id', 'custom', 'custom') ?>
              </div>

            </div>
            <!-- /row -->

            <button class="wcpt-button" wcpt-add-row-template="category-archive-override-rules">
              Add a rule
            </button>
          </div>
        <?php endif; ?>

      </div>

      <!-- attribute -->
      <div class="wcpt-toggle-options wcpt-editor-row-option" wcpt-model-key='attribute' style=" padding-top: 0">
        <div class="wcpt-editor-light-heading wcpt-toggle-label">Attribute override
          <?php echo wcpt_icon('chevron-down'); ?>
        </div>

        <div class="wcpt-editor-row-option">
          <label>Default attribute override table</label>
          <select wcpt-model-key='default'>
            <?php echo $wcpt_table_options; ?>
          </select>
          <?php wcpt_custom_shortcode_textarea('default', 'custom', 'custom') ?>
        </div>


        <!-- additional attribute overides -->
        <?php
        $wcpt_attributes = wc_get_attribute_taxonomies();
        $wcpt_attribute_select = '<select multiple wcpt-model-key="attribute">';
        foreach ($wcpt_attributes as $attribute) {
          $wcpt_attribute_select .= '<option value="' . $attribute->attribute_name . '">' . $attribute->attribute_label .
            '</option>';
        }
        $wcpt_attribute_select .= '</select>';

        ?>

        <div class="wcpt-editor-row-option" wcpt-controller="archive_override_rows" wcpt-model-key="other_rules">
          <!-- row -->
          <div class="wcpt-editor-row-option wcpt-archive-overrider-rule" wcpt-controller="archive_override_row"
            wcpt-model-key="[]" wcpt-model-key-index="0" wcpt-row-template="attribute-archive-override-rules"
            wcpt-initial-data="archive_override_rule">
            <?php wcpt_corner_options(); ?>

            <div class="wcpt-editor-row-option">
              <label>Attribute</label>
              <?php echo $wcpt_attribute_select; ?>
            </div>

            <div class="wcpt-editor-row-option">
              <label>Override table</label>
              <select wcpt-model-key='table_id'>
                <?php echo $wcpt_table_options; ?>
              </select>
              <?php wcpt_custom_shortcode_textarea('table_id', 'custom', 'custom') ?>
            </div>

          </div>
          <!-- /row -->

          <button class="wcpt-button" wcpt-add-row-template="attribute-archive-override-rules">
            Add a rule
          </button>
        </div>


      </div>

      <!-- tag -->
      <div class="wcpt-toggle-options wcpt-editor-row-option" wcpt-model-key='tag' style=" padding-top: 0">
        <div class="wcpt-editor-light-heading wcpt-toggle-label">Tag override
          <?php echo wcpt_icon('chevron-down'); ?>
        </div>

        <div class="wcpt-editor-row-option">
          <label>Default tag override table</label>
          <select wcpt-model-key='default'>
            <?php echo $wcpt_table_options; ?>
          </select>
          <?php wcpt_custom_shortcode_textarea('default', 'custom', 'custom') ?>
        </div>


        <!-- additional tag overides -->
        <?php if (wp_count_terms(array('taxonomy' => 'product_tag')) < $wcpt_option_limit): ?>

          <?php
          $wcpt_tag_select = wp_dropdown_categories(
            array(
              'echo' => 0,
              'value_field' => 'slug',
              'taxonomy' => 'product_tag',
              'name' => '',
              'id' => '',
              'class' => '',
            )
          );
          $wcpt_tag_select = str_replace('<select ', '<select multiple wcpt-model-key="tag" ', $wcpt_tag_select);
          ?>

          <div class="wcpt-editor-row-option" wcpt-controller="archive_override_rows" wcpt-model-key="other_rules">
            <!-- row -->
            <div class="wcpt-editor-row-option wcpt-archive-overrider-rule" wcpt-controller="archive_override_row"
              wcpt-model-key="[]" wcpt-model-key-index="0" wcpt-row-template="tag-archive-override-rules"
              wcpt-initial-data="archive_override_rule">
              <?php wcpt_corner_options(); ?>

              <div class="wcpt-editor-row-option">
                <label>Tag</label>
                <?php echo $wcpt_tag_select; ?>
              </div>

              <div class="wcpt-editor-row-option">
                <label>Override table</label>
                <select wcpt-model-key='table_id'>
                  <?php echo $wcpt_table_options; ?>
                </select>
                <?php wcpt_custom_shortcode_textarea('table_id', 'custom', 'custom') ?>
              </div>

            </div>
            <!-- /row -->

            <button class="wcpt-button" wcpt-add-row-template="tag-archive-override-rules">
              Add a rule
            </button>
          </div>

        <?php endif; ?>

      </div>

      <!-- brand -->
      <div class="wcpt-toggle-options wcpt-editor-row-option" wcpt-model-key='brand' style=" padding-top: 0">
        <div class="wcpt-editor-light-heading wcpt-toggle-label">Brand override
          <?php echo wcpt_icon('chevron-down'); ?>
        </div>

        <div class="wcpt-editor-row-option">
          <label>Default brand override table</label>
          <select wcpt-model-key='default'>
            <?php echo $wcpt_table_options; ?>
          </select>
          <?php wcpt_custom_shortcode_textarea('default', 'custom', 'custom') ?>
        </div>

        <?php if (wp_count_terms(array('taxonomy' => 'product_brand')) < $wcpt_option_limit): ?>
          <?php
          $wcpt_term_select = wp_dropdown_categories(
            array(
              'echo' => 0,
              'value_field' => 'slug',
              'taxonomy' => 'product_brand',
              'name' => '',
              'id' => '',
              'class' => '',
            )
          );
          $wcpt_term_select = str_replace('<select ', '<select multiple wcpt-model-key="brand" ', $wcpt_term_select);
          ?>

          <div class="wcpt-editor-row-option" wcpt-controller="archive_override_rows" wcpt-model-key="other_rules">
            <!-- row -->
            <div class="wcpt-editor-row-option wcpt-archive-overrider-rule" wcpt-controller="archive_override_row"
              wcpt-model-key="[]" wcpt-model-key-index="0" wcpt-row-template="brand-archive-override-rules"
              wcpt-initial-data="archive_override_rule">
              <?php wcpt_corner_options(); ?>

              <div class="wcpt-editor-row-option">
                <label>Brand</label>
                <?php echo $wcpt_term_select; ?>
              </div>

              <div class="wcpt-editor-row-option">
                <label>Override table</label>
                <select wcpt-model-key='table_id'>
                  <?php echo $wcpt_table_options; ?>
                </select>
                <?php wcpt_custom_shortcode_textarea('table_id', 'custom', 'custom') ?>
              </div>

            </div>
            <!-- /row -->

            <button class="wcpt-button" wcpt-add-row-template="brand-archive-override-rules">
              Add a rule
            </button>
          </div>
        <?php endif; ?>
      </div>

    </div>

  </div>

</div>