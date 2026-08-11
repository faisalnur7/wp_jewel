<?php
/**
 * AJAX controller.
 *
 * @package BulkVariationCodeGenerator
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class BVCG_Ajax {

	/**
	 * Generator service.
	 *
	 * @var BVCG_Generator
	 */
	private $generator;

	/**
	 * Constructor.
	 *
	 * @param BVCG_Generator $generator Generator service.
	 */
	public function __construct( BVCG_Generator $generator ) {
		$this->generator = $generator;

		add_action( 'wp_ajax_bvcg_preview', array( $this, 'preview' ) );
		add_action( 'wp_ajax_bvcg_create_job', array( $this, 'create_job' ) );
		add_action( 'wp_ajax_bvcg_process_job', array( $this, 'process_job' ) );
	}

	/**
	 * Returns a validation preview.
	 *
	 * @return void
	 */
	public function preview() {
		$this->verify_request();

		$payload = $this->get_payload_from_request();
		$result  = $this->generator->normalize_payload( $payload );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error(
				array(
					'message' => $result->get_error_message(),
				)
			);
		}

		wp_send_json_success(
			array(
				'preview' => $this->generator->build_preview( $result['items'] ),
			)
		);
	}

	/**
	 * Creates a generation job.
	 *
	 * @return void
	 */
	public function create_job() {
		$this->verify_request();

		$result = $this->generator->create_job( $this->get_payload_from_request() );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error(
				array(
					'message' => $result->get_error_message(),
				)
			);
		}

		wp_send_json_success( $result );
	}

	/**
	 * Processes a generation job batch.
	 *
	 * @return void
	 */
	public function process_job() {
		$this->verify_request();

		$token = isset( $_POST['job_token'] ) ? sanitize_text_field( wp_unslash( $_POST['job_token'] ) ) : '';
		$batch = isset( $_POST['batch_size'] ) ? absint( $_POST['batch_size'] ) : BVCG_Generator::DEFAULT_BATCH_SIZE;

		if ( '' === $token ) {
			wp_send_json_error(
				array(
					'message' => __( 'Missing job token.', 'bulk-variation-code-generator' ),
				)
			);
		}

		$result = $this->generator->process_job( $token, $batch );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error(
				array(
					'message' => $result->get_error_message(),
				)
			);
		}

		wp_send_json_success( $result );
	}

	/**
	 * Verifies nonce and capability checks.
	 *
	 * @return void
	 */
	private function verify_request() {
		check_ajax_referer( 'bvcg_generate_variations', 'nonce' );

		$product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;

		if ( ! $product_id || ! current_user_can( 'edit_post', $product_id ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'You do not have permission to edit this product.', 'bulk-variation-code-generator' ),
				)
			);
		}
	}

	/**
	 * Builds a sanitized request payload.
	 *
	 * @return array
	 */
	private function get_payload_from_request() {
		$payload = array(
			'product_id'  => isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0,
			'attribute'   => isset( $_POST['attribute'] ) ? sanitize_text_field( wp_unslash( $_POST['attribute'] ) ) : '',
			'prefix'      => isset( $_POST['prefix'] ) ? wp_unslash( $_POST['prefix'] ) : '',
			'start'       => isset( $_POST['start'] ) ? absint( $_POST['start'] ) : 0,
			'end'         => isset( $_POST['end'] ) ? absint( $_POST['end'] ) : 0,
			'digits'      => isset( $_POST['digits'] ) ? absint( $_POST['digits'] ) : 3,
			'price'       => isset( $_POST['price'] ) ? wp_unslash( $_POST['price'] ) : '',
			'sku_prefix'  => isset( $_POST['sku_prefix'] ) ? wp_unslash( $_POST['sku_prefix'] ) : '',
			'stock_qty'   => isset( $_POST['stock_qty'] ) ? absint( $_POST['stock_qty'] ) : 100,
			'csv_text'    => isset( $_POST['csv_text'] ) ? wp_unslash( $_POST['csv_text'] ) : '',
		);

		if ( ! empty( $_POST['ranges'] ) ) {
			$ranges_raw = wp_unslash( $_POST['ranges'] );
			$ranges     = json_decode( $ranges_raw, true );

			if ( is_array( $ranges ) ) {
				$payload['ranges'] = $ranges;
			}
		}

		return $payload;
	}
}

