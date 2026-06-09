<!-- file source -->
<div class="wcpt-editor-row-option">
  <label>
    Audio file source
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

<!-- player type -->
<div class="wcpt-editor-row-option">
  <label>
    Audio player type
  </label>
  <select wcpt-model-key="player_type">
    <option value="wordpress_audio_player">WordPress audio player</option>
    <option value="compact_audio_player">Compact audio player</option>
  </select>
</div>

<?php wcpt_editor_more_options_container_start(); ?>

<!-- loop -->
<div class="wcpt-editor-row-option">
  <label>
    <input type="checkbox" wcpt-model-key="loop" />
    Loop audio track infinitely
  </label>
</div>

<div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="player_type"
  wcpt-condition-val="compact_audio_player">
  <!-- second click -->
  <div class="wcpt-editor-row-option">
    <label>
      Action on second click
    </label>
    <select wcpt-model-key="second_click_action">
      <option value="pause">Pause</option>
      <option value="stop">Stop and reset</option>
    </select>
  </div>
  <!-- secondary icon -->
  <div class="wcpt-editor-row-option">
    <label>
      Icon while playing audio
    </label>
    <select wcpt-model-key="playing_icon">
      <option value="pause">Pause</option>
      <option value="stop">Stop</option>
      <option value="cross">Cross</option>
      <option value="music">Music</option>
    </select>
  </div>
</div>

<?php wcpt_editor_more_options_container_end(); ?>

<div class="wcpt-editor-row-option">
  <label>HTML Class</label>
  <input type="text" wcpt-model-key="html_class" />
</div>

<!-- style -->
<div class="wcpt-editor-row-option" wcpt-model-key="style" wcpt-panel-condition="prop" wcpt-condition-prop="player_type"
  wcpt-condition-val="compact_audio_player">

  <?php $player_selector = '[id].wcpt-audio-player--compact_audio_player'; ?>

  <!-- style for compact audio player -->
  <div class="wcpt-editor-row-option">
    <div class="wcpt-toggle-options wcpt-row-accordion">

      <span class="wcpt-toggle-label">
        <?php echo wcpt_icon('paint-brush'); ?>
        Style for Compact Audio Player
        <?php echo wcpt_icon('chevron-down'); ?>
      </span>

      <!-- width -->
      <div class="wcpt-editor-row-option" wcpt-model-key="<?php echo $player_selector; ?> .wcpt-player">
        <label>Width</label>
        <input type="text" wcpt-model-key="font-size" placeholder="30px" />
      </div>

      <div class="wcpt-editor-row-option" wcpt-model-key="<?php echo $player_selector; ?> .wcpt-player__button">

        <!-- background color -->
        <div class="wcpt-editor-row-option">
          <label>Background color</label>
          <input type="text" wcpt-model-key="background-color" class="wcpt-color-picker">
        </div>

        <!-- icon color -->
        <div class="wcpt-editor-row-option">
          <label>Icon color</label>
          <input type="text" wcpt-model-key="color" class="wcpt-color-picker">
        </div>

        <!-- border -->
        <div class="wcpt-editor-row-option">
          <label>Border</label>
          <div class="wcpt-flex-option-container">
            <input type="text" wcpt-model-key="border-width" placeholder="width">
            <input type="text" wcpt-model-key="border-color" class="wcpt-color-picker" placeholder="color">
          </div>
        </div>

        <!-- border-radius -->
        <div class="wcpt-editor-row-option">
          <label>Border radius</label>
          <input type="text" wcpt-model-key="border-radius">
        </div>
      </div>

    </div>
  </div>

  <!-- style while playing audio -->
  <div class="wcpt-editor-row-option">
    <div class="wcpt-toggle-options wcpt-row-accordion">

      <span class="wcpt-toggle-label">
        <?php echo wcpt_icon('paint-brush'); ?>
        Style while playing audio
        <?php echo wcpt_icon('chevron-down'); ?>
      </span>

      <div class="wcpt-editor-row-option"
        wcpt-model-key="<?php echo $player_selector; ?> .wcpt-player--playing .wcpt-player__button">

        <!-- background color -->
        <div class="wcpt-editor-row-option">
          <label>Background color</label>
          <input type="text" wcpt-model-key="background-color" class="wcpt-color-picker">
        </div>

        <!-- icon color -->
        <div class="wcpt-editor-row-option">
          <label>Icon color</label>
          <input type="text" wcpt-model-key="color" class="wcpt-color-picker">
        </div>

        <!-- border -->
        <div class="wcpt-editor-row-option">
          <label>Border color</label>
          <input type="text" wcpt-model-key="border-color" class="wcpt-color-picker" placeholder="color">
        </div>

      </div>

    </div>

  </div>

</div>

<div class="wcpt-editor-row-option" wcpt-model-key="style" wcpt-panel-condition="prop" wcpt-condition-prop="player_type"
  wcpt-condition-val="wordpress_audio_player">

  <?php $player_selector = '[id].wcpt-audio-player--wordpress_audio_player'; ?>

  <div class="wcpt-editor-row-option">
    <div class="wcpt-toggle-options wcpt-row-accordion">

      <span class="wcpt-toggle-label">
        <?php echo wcpt_icon('paint-brush'); ?>
        Style for WordPress Audio Player
        <?php echo wcpt_icon('chevron-down'); ?>
      </span>

      <!-- width -->
      <div class="wcpt-editor-row-option" wcpt-model-key="<?php echo $player_selector; ?>">
        <label>Width</label>
        <input type="text" wcpt-model-key="width" />
      </div>

      <div wcpt-model-key="<?php echo $player_selector; ?> .mejs-container">

        <!-- background color -->
        <div class="wcpt-editor-row-option">
          <label>Background color</label>
          <input type="text" wcpt-model-key="background-color" class="wcpt-color-picker">
        </div>

        <!-- border -->
        <div class="wcpt-editor-row-option">
          <label>Border</label>
          <div class="wcpt-flex-option-container">
            <input type="text" wcpt-model-key="border-width" placeholder="width">
            <input type="text" wcpt-model-key="border-color" class="wcpt-color-picker" placeholder="color">
          </div>
        </div>

        <!-- border-radius -->
        <div class="wcpt-editor-row-option">
          <label>Border radius</label>
          <input type="text" wcpt-model-key="border-radius">
        </div>

      </div>

      <!-- icon color -->
      <div class="wcpt-editor-row-option" wcpt-model-key="<?php echo $player_selector; ?> .mejs-button > button">
        <label>Icon color</label>
        <input type="text" wcpt-model-key="color" class="wcpt-color-picker">
      </div>

      <!-- time color -->
      <div class="wcpt-editor-row-option" wcpt-model-key="<?php echo $player_selector; ?> .mejs-time">
        <label>Time color</label>
        <input type="text" wcpt-model-key="color" class="wcpt-color-picker">
      </div>

      <!-- progress rail current -->
      <div class="wcpt-editor-row-option"
        wcpt-model-key="<?php echo $player_selector; ?> .mejs-controls .mejs-time-rail .mejs-time-current">
        <label>Progress indicator color</label>
        <input type="text" wcpt-model-key="background-color" class="wcpt-color-picker">
      </div>

      <!-- progress rail background -->
      <div class="wcpt-editor-row-option"
        wcpt-model-key="<?php echo $player_selector; ?> .mejs-controls .mejs-time-rail .mejs-time-total">
        <label>Progress background color</label>
        <input type="text" wcpt-model-key="background-color" class="wcpt-color-picker">
      </div>

      <!-- progress rail loaded -->
      <div class="wcpt-editor-row-option"
        wcpt-model-key="<?php echo $player_selector; ?> .mejs-controls .mejs-time-rail .mejs-time-loaded">
        <label>Progress loaded color</label>
        <input type="text" wcpt-model-key="background-color" class="wcpt-color-picker">
      </div>

    </div>
  </div>

  <!-- style while playing audio -->
  <div class="wcpt-editor-row-option">
    <div class="wcpt-toggle-options wcpt-row-accordion">

      <span class="wcpt-toggle-label">
        <?php echo wcpt_icon('paint-brush'); ?>
        Style while playing audio
        <?php echo wcpt_icon('chevron-down'); ?>
      </span>

      <div wcpt-model-key="<?php echo $player_selector; ?> .mejs-container.wcpt-mejs-playing">

        <!-- background color -->
        <div class="wcpt-editor-row-option">
          <label>Background color</label>
          <input type="text" wcpt-model-key="background-color" class="wcpt-color-picker">
        </div>

        <!-- border color -->
        <div class="wcpt-editor-row-option">
          <label>Border color</label>
          <input type="text" wcpt-model-key="border-color" class="wcpt-color-picker" placeholder="color">
        </div>

      </div>

      <!-- icon color -->
      <div class="wcpt-editor-row-option"
        wcpt-model-key="<?php echo $player_selector; ?> .wcpt-mejs-playing .mejs-button > button">
        <label>Icon color</label>
        <input type="text" wcpt-model-key="color" class="wcpt-color-picker">
      </div>

      <!-- time color -->
      <div class="wcpt-editor-row-option"
        wcpt-model-key="<?php echo $player_selector; ?> .wcpt-mejs-playing .mejs-time">
        <label>Time color</label>
        <input type="text" wcpt-model-key="color" class="wcpt-color-picker">
      </div>

      <!-- progress rail current -->
      <div class="wcpt-editor-row-option"
        wcpt-model-key="<?php echo $player_selector; ?> .wcpt-mejs-playing .mejs-controls .mejs-time-rail .mejs-time-current">
        <label>Progress indicator color</label>
        <input type="text" wcpt-model-key="background-color" class="wcpt-color-picker">
      </div>

      <!-- progress rail background -->
      <div class="wcpt-editor-row-option"
        wcpt-model-key="<?php echo $player_selector; ?> .wcpt-mejs-playing .mejs-controls .mejs-time-rail .mejs-time-total">
        <label>Progress background color</label>
        <input type="text" wcpt-model-key="background-color" class="wcpt-color-picker">
      </div>

      <!-- progress rail loaded -->
      <div class="wcpt-editor-row-option"
        wcpt-model-key="<?php echo $player_selector; ?> .wcpt-mejs-playing .mejs-controls .mejs-time-rail .mejs-time-loaded">
        <label>Progress loaded color</label>
        <input type="text" wcpt-model-key="background-color" class="wcpt-color-picker">
      </div>

    </div>
  </div>


</div>

<!-- condition -->
<?php include('condition/outer.php'); ?>