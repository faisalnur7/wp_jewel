<?php
/**
 * Main plugin bootstrap.
 *
 * @package BulkVariationCodeGenerator
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class BVCG_Plugin {

	/**
	 * Singleton instance.
	 *
	 * @var BVCG_Plugin|null
	 */
	private static $instance = null;

	/**
	 * Admin controller.
	 *
	 * @var BVCG_Admin
	 */
	private $admin;

	/**
	 * AJAX controller.
	 *
	 * @var BVCG_Ajax
	 */
	private $ajax;

	/**
	 * Generator service.
	 *
	 * @var BVCG_Generator
	 */
	private $generator;

	/**
	 * Returns singleton instance.
	 *
	 * @return BVCG_Plugin
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Plugin activation hook.
	 *
	 * @return void
	 */
	public static function activate() {
		add_option( 'bvcg_version', BVCG_VERSION );
	}

	/**
	 * Plugin deactivation hook.
	 *
	 * @return void
	 */
	public static function deactivate() {
		global $wpdb;

		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
				$wpdb->esc_like( '_transient_bvcg_job_' ) . '%'
			)
		);

		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
				$wpdb->esc_like( '_transient_timeout_bvcg_job_' ) . '%'
			)
		);

		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
				$wpdb->esc_like( 'bvcg_lock_' ) . '%'
			)
		);
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		add_action( 'init', array( $this, 'load_textdomain' ) );
		add_action( 'admin_notices', array( $this, 'maybe_show_dependency_notice' ) );

		if ( ! $this->is_woocommerce_active() ) {
			return;
		}

		$this->includes();
		$this->generator = new BVCG_Generator();
		$this->ajax      = new BVCG_Ajax( $this->generator );
		$this->admin     = new BVCG_Admin( $this->generator );
	}

	/**
	 * Loads plugin text domain.
	 *
	 * @return void
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'bulk-variation-code-generator', false, dirname( plugin_basename( BVCG_FILE ) ) . '/languages' );
	}

	/**
	 * Checks WooCommerce availability.
	 *
	 * @return bool
	 */
	private function is_woocommerce_active() {
		return class_exists( 'WooCommerce' ) && function_exists( 'WC' );
	}

	/**
	 * Includes class files.
	 *
	 * @return void
	 */
	private function includes() {
		require_once BVCG_PATH . 'includes/class-bvcg-generator.php';
		require_once BVCG_PATH . 'includes/class-bvcg-ajax.php';
		require_once BVCG_PATH . 'includes/class-bvcg-admin.php';
	}

	/**
	 * Displays dependency notice when WooCommerce is missing.
	 *
	 * @return void
	 */
	public function maybe_show_dependency_notice() {
		if ( $this->is_woocommerce_active() ) {
			return;
		}

		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		echo '<div class="notice notice-error"><p>' . esc_html__( 'Bulk Variation Code Generator requires WooCommerce to be active.', 'bulk-variation-code-generator' ) . '</p></div>';
	}
}
