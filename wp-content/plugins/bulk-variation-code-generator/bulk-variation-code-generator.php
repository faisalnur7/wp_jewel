<?php
/**
 * Plugin Name: Bulk Variation Code Generator
 * Description: Generate WooCommerce product variations from sequential code ranges.
 * Version: 1.0.0
 * Author: Faisal Nur
 * Text Domain: bulk-variation-code-generator
 * Requires Plugins: woocommerce
 *
 * @package BulkVariationCodeGenerator
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BVCG_VERSION', '1.0.0' );
define( 'BVCG_FILE', __FILE__ );
define( 'BVCG_PATH', plugin_dir_path( __FILE__ ) );
define( 'BVCG_URL', plugin_dir_url( __FILE__ ) );

require_once BVCG_PATH . 'includes/class-bvcg-plugin.php';

register_activation_hook( __FILE__, array( 'BVCG_Plugin', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'BVCG_Plugin', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'BVCG_Plugin', 'instance' ) );
