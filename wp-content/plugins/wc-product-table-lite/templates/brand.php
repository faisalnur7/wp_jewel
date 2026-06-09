<?php
if (!defined('ABSPATH')) {
  exit;
}

$taxonomy = 'product_brand';
$html_class .= ' wcpt-brands';
$term_html_class = 'wcpt-brand';
$term_separator_html_class = 'wcpt-brand-term-separator';

include('taxonomy.php');