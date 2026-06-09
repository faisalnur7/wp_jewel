<?php
// locate and include partials - nav + cell template + heading content
$partial_names = array_diff(scandir(__DIR__ . '/partials'), array('..', '.', '.DS_Store'));

$partials = array();
foreach ($partial_names as $partial_name) {
  $partials[] = array(
    'name' => $partial_name,
    'location' => 'partials/' . $partial_name,
  );
}

$partials = apply_filters('wcpt_partials_array', $partials);

foreach ($partials as $partial) {
  if (substr($partial['name'], -4) == '.php') {
    echo '<script type="text/template" data-wcpt-partial="' . substr($partial['name'], 0, -4) . '">';
    if (
      'add' != substr($partial['name'], 0, 3) ||
      'add_selected_to_cart.php' == $partial['name']
    ) {
      $x1 = explode('__', substr($partial['name'], 0, -4));
      $element_name = ucwords(implode(' ', explode('_', $x1[0])));

      switch ($element_name) {
        case 'Apply Reset':
          $element_name = 'Apply / Reset';
          break;

        case 'Html':
          $element_name = 'HTML';
          break;

        case 'Download Csv':
          $element_name = 'Download CSV';
          break;

        case 'Gtin':
          $element_name = 'GTIN';
          break;

        case 'Sku':
          $element_name = 'SKU';
          break;
      }

      echo '<h2>Edit element: \'' . $element_name . '\'</h2>';

      // show pro required notice if element is pro required
      if (!defined('WCPT_PRO')) {
        ?>
        <div class="wcpt-editor-row-option" wcpt-panel-condition="prop" wcpt-condition-prop="_pro_required"
          wcpt-condition-val="true">
          <span class="wcpt-notice">
            Note: This element requires PRO version of the plugin. Please purchase PRO version from <a
              href="https://wcproducttable.com/" target="_blank">wcproducttable.com</a>.
          </span>
        </div>
        <?php
      }

    }

    // include body of the partial
    include(apply_filters('wcpt_partial', $partial['location'], $partial['name']));
    echo '</script>';
  }
}
?>