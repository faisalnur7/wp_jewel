<style>
  .wcpt-import-export-wrapper {
    margin: 20px 0;
  }

  .wcpt-import-export-button {
    padding: 16px 22px;
    background: #fff;
    border: 1px solid #bbb;
    border-radius: 4px;
    display: inline-block;
    font-size: 18px;
    margin-right: 10px;
    transition: .2s background-color;
  }

  .wcpt-import-icon svg,
  .wcpt-export-icon svg {
    height: .9em;
    stroke-width: 2.5px;
    vertical-align: baseline;
    position: relative;
    top: 1px;
  }

  .wcpt-import-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, .5);
    z-index: 10000;
  }

  .wcpt-import-modal>form {
    background: white;
    width: 300px;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    padding: 40px;
    font-size: 16px;
    line-height: 1.5em;
    border-radius: 5px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
  }

  .wcpt-import-modal>form:after {
    content: '';
    position: absolute;
    left: 10px;
    top: 10px;
    border: 2px solid #f7f7f7;
    width: calc(100% - 20px);
    height: calc(100% - 20px);
    border-radius: inherit;
    box-sizing: border-box;
    pointer-events: none;
  }

  .wcpt-import-modal>form>h2 {
    font-size: 20px;
    font-weight: bold;
    margin: .25em 0 1em;
  }

  .wcpt-import-modal>form>ol {
    padding-left: 1em;
    margin: 1em 0;
  }

  .wcpt-show-import-modal>.wcpt-import-modal {
    display: block;
  }

  .wcpt-export-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, .5);
    z-index: 10000;
  }

  .wcpt-export-modal>form {
    background: white;
    width: min(440px, 92vw);
    max-height: min(560px, 85vh);
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    padding: 40px;
    font-size: 16px;
    line-height: 1.5em;
    border-radius: 5px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    box-sizing: border-box;
  }

  .wcpt-export-modal>form:after {
    content: '';
    position: absolute;
    left: 10px;
    top: 10px;
    border: 2px solid #f7f7f7;
    width: calc(100% - 20px);
    height: calc(100% - 20px);
    border-radius: inherit;
    box-sizing: border-box;
    pointer-events: none;
  }

  .wcpt-export-modal>form>h2 {
    font-size: 20px;
    font-weight: bold;
    margin: .25em 0 .5em;
    flex-shrink: 0;
  }

  .wcpt-export-modal>form>.wcpt-export-tables-intro {
    margin: 0 0 1em;
    flex-shrink: 0;
  }

  .wcpt-export-select-all-wrap {
    margin-bottom: 10px;
    flex-shrink: 0;
  }

  .wcpt-export-select-all-wrap label {
    cursor: pointer;
    font-weight: 600;
  }

  .wcpt-export-tables-checklist {
    list-style: none;
    margin: 0 0 1em;
    padding: 0;
    overflow-y: auto;
    flex: 1;
    min-height: 80px;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 10px 12px;
  }

  .wcpt-export-tables-checklist li {
    margin: 0 0 8px;
    padding: 0;
  }

  .wcpt-export-tables-checklist li:last-child {
    margin-bottom: 0;
  }

  .wcpt-export-tables-checklist label {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    cursor: pointer;
  }

  .wcpt-export-tables-checklist input[type="checkbox"] {
    margin-top: 3px;
  }

  .wcpt-export-modal-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    align-items: center;
    margin-top: 6px;
    flex-shrink: 0;
  }

  .wcpt-export-modal-actions .wcpt-import-export-button {
    margin: 0;
  }

  .wcpt-export-modal .wcpt-export-modal-cancel {
    background: #f0f0f0;
  }

  .wcpt-show-export-modal>.wcpt-export-modal {
    display: block;
  }

  .wcpt-show-import-modal input[type="file"] {
    margin: 4px 0 8px;
    border: 2px solid rgba(0, 0, 0, 0.04);
    border-width: 2px 0px;
    padding: 16px 0;
  }

  .wcpt-show-import-modal input[type="submit"] {
    margin-top: 14px;
    font-size: 16px;
    padding: 10px 25px;
  }

  .wcpt-pro .wcpt-import-export-button {
    cursor: pointer;
    box-shadow: 1px 1px 1px rgba(0, 0, 0, .04);
  }

  .wcpt-pro .wcpt-import-export-button:hover {
    background: #f9f9f9;
    box-shadow: 0px 0px 0px rgba(0, 0, 0, .06);
  }

  .wcpt-import-export-button .wcpt-pro-badge {
    border-radius: 3px;
    font-size: 12px;
    background: #EF5350;
    color: white;
    padding: 4px 8px;
    margin-left: .75em;
    vertical-align: middle;
  }
</style>

<?php
if (empty($wcpt_import_export_button_label_append)) {
  $wcpt_import_export_button_label_append = 'settings';
}

if (empty($wcpt_import_export_button_context)) {
  $wcpt_import_export_button_context = 'settings';
}

$wcpt_export_tables_list = array();
if ($wcpt_import_export_button_context === 'tables') {
  $wcpt_export_tables_list = get_posts(
    array(
      'post_type' => 'wc_product_table',
      'post_status' => 'publish',
      'posts_per_page' => -1,
      'orderby' => 'title',
      'order' => 'ASC',
    )
  );
}
?>
<div class="wcpt-import-export-wrapper ">
  <span class="wcpt-import-button wcpt-import-export-button">
    <?php wcpt_icon('download', 'wcpt-import-icon'); ?>
    Import
    <?php echo $wcpt_import_export_button_label_append; ?>
    <?php wcpt_pro_badge(); ?>
  </span>
  <span class="wcpt-export-button wcpt-import-export-button">
    <?php wcpt_icon('upload', 'wcpt-export-icon'); ?>
    Export
    <?php echo $wcpt_import_export_button_label_append; ?>
    <?php wcpt_pro_badge(); ?>
  </span>
  <div class="wcpt-import-modal">
    <form method="POST" enctype="multipart/form-data">

      <h2>Import <?php echo $wcpt_import_export_button_label_append; ?></h2>

      <span>During demo import, please follow:</span>
      <ol>
        <li>Backup site database</li>
        <li>Import WooCommerce products</li>
        <li>Import the product tables</li>
        <li>Import the table settings</li>
      </ol>

      <input type="file" name="wcpt_import_file">
      <br>
      <input type="submit" class="wcpt-import-export-button" />

      <input type="hidden" name="wcpt_import_export_nonce"
        value="<?php echo wp_create_nonce('wcpt_import_export'); ?>" />
      <input type="hidden" name="wcpt_context" value="<?php echo $wcpt_import_export_button_context; ?>" />
      <input type="hidden" name="wcpt_action" />
      <input type="hidden" name="wcpt_export_id" value="" />
    </form>
  </div>
  <?php if ($wcpt_import_export_button_context === 'tables'): ?>
    <div class="wcpt-export-modal">
      <form method="POST">
        <h2>Export product tables</h2>
        <p class="wcpt-export-tables-intro">Select which tables to include in the download. The file format is the same as a full export (<code>wcpt_tables.json</code>).</p>
        <?php if (!empty($wcpt_export_tables_list)): ?>
          <div class="wcpt-export-select-all-wrap">
            <label>
              <input type="checkbox" class="wcpt-export-select-all" />
              Select all
            </label>
          </div>
          <ul class="wcpt-export-tables-checklist">
            <?php foreach ($wcpt_export_tables_list as $wcpt_export_table_post): ?>
              <li>
                <label>
                  <input type="checkbox" class="wcpt-export-table-cb" value="<?php echo esc_attr((string) $wcpt_export_table_post->ID); ?>" />
                  <span><?php echo esc_html($wcpt_export_table_post->post_title); ?></span>
                </label>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <p>There are no published product tables to export yet.</p>
        <?php endif; ?>
        <div class="wcpt-export-modal-actions">
          <?php if (!empty($wcpt_export_tables_list)): ?>
            <input type="submit" class="wcpt-import-export-button wcpt-export-modal-submit" value="Export selected" />
          <?php endif; ?>
          <button type="button" class="wcpt-import-export-button wcpt-export-modal-cancel">Cancel</button>
        </div>
        <input type="hidden" name="wcpt_import_export_nonce"
          value="<?php echo wp_create_nonce('wcpt_import_export'); ?>" />
        <input type="hidden" name="wcpt_context" value="tables" />
        <input type="hidden" name="wcpt_action" value="export" />
        <input type="hidden" name="wcpt_export_id" value="" />
      </form>
    </div>
  <?php endif; ?>
</div>

<?php if (defined('WCPT_PRO')): ?>

  <script>
    (function ($) {
      $(function () {

        // import
        $('body').on('click', '.wcpt-import-button', function () {
          var $this = $(this),
            $wrapper = $this.parent();
          $wrapper.removeClass('wcpt-show-export-modal');
          $wrapper.addClass('wcpt-show-import-modal');
          $('input[name="wcpt_action"]', $wrapper).val('import');
        })

        $('body').on('click', '.wcpt-import-modal', function (e) {
          if (e.target === this) {
            var cl = 'wcpt-show-import-modal';
            $(this).closest('.' + cl).removeClass(cl);
          }
        })

        // export (settings: full settings JSON; tables: open selection modal)
        $('body').on('click', '.wcpt-export-button', function (e) {
          var $this = $(this),
            $wrapper = $this.parent();
          if ($wrapper.find('.wcpt-export-modal').length) {
            e.preventDefault();
            $wrapper.removeClass('wcpt-show-import-modal');
            $wrapper.addClass('wcpt-show-export-modal');
            $wrapper.find('.wcpt-export-select-all').prop('checked', false);
            $wrapper.find('.wcpt-export-table-cb').prop('checked', false);
          } else {
            $('input[name="wcpt_action"]', $wrapper).val('export');
            $wrapper.find('form').submit();
          }
        })

        $('body').on('click', '.wcpt-export-modal', function (e) {
          if (e.target === this) {
            $(this).closest('.wcpt-import-export-wrapper').removeClass('wcpt-show-export-modal');
          }
        })

        $('body').on('click', '.wcpt-export-modal-cancel', function () {
          $(this).closest('.wcpt-import-export-wrapper').removeClass('wcpt-show-export-modal');
        })

        $('body').on('change', '.wcpt-export-select-all', function () {
          var on = $(this).prop('checked');
          $(this).closest('.wcpt-export-modal').find('.wcpt-export-table-cb').prop('checked', on);
        })

        $('body').on('submit', '.wcpt-export-modal form', function (e) {
          var $form = $(this),
            ids = [];
          $form.find('.wcpt-export-table-cb:checked').each(function () {
            ids.push($(this).val());
          });
          if (!ids.length) {
            e.preventDefault();
            window.alert('Please select at least one table to export.');
          } else {
            $form.find('input[name="wcpt_export_id"]').val(ids.join(','));
          }
        })

        // export single table (submit only the import modal form — not the export selection modal)
        $('body').on('click', '.wcpt-export-single-table', function () {
          var $this = $(this),
            table_id = $this.data('id'),
            $wrapper = $('.wcpt-import-export-wrapper');
          $('input[name="wcpt_action"]', $wrapper).val('export');
          $('input[name="wcpt_export_id"]', $wrapper).val(table_id);
          $wrapper.find('.wcpt-import-modal form').submit();
        })
      })

    })(jQuery)
  </script>
<?php endif; ?>