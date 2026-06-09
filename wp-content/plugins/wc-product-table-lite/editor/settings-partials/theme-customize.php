<div class="wcpt-toggle-options" wcpt-model-key="theme_customize">
  <div class="wcpt-editor-light-heading wcpt-toggle-label">
    Live theme customizer <?php echo wcpt_icon('chevron-down'); ?>
  </div>

  <div class="wcpt-editor-row-option">
    <div class="wcpt-editor-customize-theme" style="display: flex; flex-direction: column; gap: 10px;">
      <div style="display: flex; gap: 10px;">

        <select>
          <option value="">Select product table to preview theme</option>
          <?php
          $tables = get_posts(array(
            'post_type' => 'wc_product_table',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC'
          ));

          foreach ($tables as $table) {
            printf(
              '<option value="%s">%s</option>',
              get_permalink($table->ID),
              esc_html($table->post_title)
            );
          }
          ?>
        </select>
      </div>
      <div style="display: flex;
          gap: 10px;
          flex-wrap: nowrap;
          align-items: center;
      ">

        <!-- customize -->
        <button class="wcpt-editor-customize-theme-button" data-wcpt-purpose="customize-theme" href="#"
          target="_self"><?php wcpt_icon('paint-brush'); ?>Customize theme</button>

        <?php
        $theme_buttons = [
          'reset-theme' => [
            'nonce_action' => 'wcpt_theme_reset_nonce',
            'icon' => 'rotate-cw',
            'label' => 'Reset theme'
          ],
          'export-theme' => [
            'nonce_action' => 'wcpt_theme_export_nonce',
            'icon' => 'upload',
            'label' => 'Export theme'
          ],
          'import-theme' => [
            'nonce_action' => 'wcpt_theme_import_nonce',
            'icon' => 'download',
            'label' => 'Import theme'
          ]
        ];

        foreach ($theme_buttons as $purpose => $button): ?>
          <?php if ($purpose === 'import-theme' || $purpose === 'export-theme')
            continue; ?>
          <button class="wcpt-editor-customize-theme-button" data-wcpt-purpose="<?php echo $purpose; ?>" href="#"
            data-wcpt-nonce="<?php echo wp_create_nonce($button['nonce_action']); ?>" target="_self">
            <i class="wcpt-resetting-icon wcpt-hide">
              <?php wcpt_icon('loader', 'wcpt-rotate'); ?>
            </i>
            <span class="wcpt-original-icon"><?php wcpt_icon($button['icon']); ?></span>
            <?php echo $button['label']; ?>
          </button>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <div class="wcpt-editor-row-option">
    <strong>Note:</strong> Theme customizations made here will apply globally to all tables. However, any style
    properties set in
    individual table settings will take precedence over these global theme settings.
  </div>
</div>