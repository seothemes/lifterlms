<?php
/**
 * Handle extra profile fields for users in admin
 *
 * @package LifterLMS/Admin/Classes
 *
 * @since [verson]
 * @version [version]
 */

defined( 'ABSPATH' ) || exit;

/**
 * Handle extra profile fields for users in admin
 *
 * Applies to edit-user.php & profile.php.
 *
 * @since [version]
 */
class LLMS_Admin_Profile {

	/**
	 * Array of user profile fields
	 *
	 * @var array
	 */
	private $fields;

	/**
	 * Submission errors
	 *
	 * @var null|WP_Error
	 */
	private $errors;

	/**
	 * Constructor
	 *
	 * @since [version]
	 *
	 * @return void
	 */
	public function __construct() {

		add_action( 'show_user_profile', array( $this, 'add_customer_meta_fields' ) );
		add_action( 'edit_user_profile', array( $this, 'add_customer_meta_fields' ) );

		add_action( 'personal_options_update', array( $this, 'save_customer_meta_fields' ) );
		add_action( 'edit_user_profile_update', array( $this, 'save_customer_meta_fields' ) );

		// Allow errors to be output.
		add_action( 'user_profile_update_errors', array( $this, 'add_errors' ) );

	}

	/**
	 * Add customer meta fields to the profile screens
	 *
	 * @since [version]
	 *
	 * @param WP_User $user Instance of WP_User for the user being updated.
	 * @return bool `true` if fields were added, `false` otherwise.
	 */
	public function add_customer_meta_fields( $user ) {

		if ( ! $this->current_user_can_edit_admin_custom_fields() ) {
			return false;
		}

		$fields = $this->get_fields();

		if ( empty( $fields ) ) {
			return false;
		}

		/**
		 * Enqueue select2 scripts and styles.
		 */
		wp_enqueue_script( 'llms-metaboxes' );
		wp_enqueue_script( 'llms-select2' );
		llms()->assets->enqueue_style( 'llms-select2-styles' );
		wp_add_inline_script(
			'llms',
			"window.llms.address_info = '" . wp_json_encode( llms_get_countries_address_info() ) . "';"
		);

		include_once LLMS_PLUGIN_DIR . 'includes/admin/views/user-edit-fields.php';

		return true;

	}

	/**
	 * Maybe save customer meta fields
	 *
	 * @since [version]
	 *
	 * @param int $user_id WP_User ID for the user being updated.
	 * @return void
	 */
	public function save_customer_meta_fields( $user_id ) {

		if ( ! $this->current_user_can_edit_admin_custom_fields() ) {
			return;
		}

		$fields      = $this->get_fields();
		$posted_data = array();

		foreach ( $this->fields as $field ) {
			//phpcs:disable WordPress.Security.NonceVerification.Missing  -- nonce is verified prior to reaching this method.
			if ( isset( $_POST[ $field['name'] ] ) &&
					isset( $field['data_store_key'] ) &&
						$field['data_store'] && 'usermeta' === $field['data_store'] ) {
				//phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash -- sanitization and unslashing happens in `LLMS_Form_Handler::instance()->submit_form_fields()` below.
				$posted_data[ $field['name'] ] = $_POST[ $field['name'] ];
			}
			//phpcs:disable WordPress.Security.NonceVerification.Missing
		}

		if ( empty( $posted_data ) ) {
			return;
		}

		$posted_data['user_id'] = $user_id;

		$submit = LLMS_Form_Handler::instance()->submit_fields( $posted_data, 'admin-profile', $fields, 'update' );

		if ( is_wp_error( $submit ) ) {
			$this->errors = $submit;
		}

	}

	/**
	 * Maybe print validation errors
	 *
	 * @since [version]
	 *
	 * @param WP_Error $errors Instance of WP_Error, passed by reference.
	 * @return void
	 */
	public function add_errors( &$errors ) {

		if ( is_wp_error( $this->errors ) && $this->errors->has_errors() ) {
			$this->merge_llms_fields_errors( $errors );
		}

	}

	/**
	 * Check whether the current user can edit users custom fields
	 *
	 * @since [version]
	 *
	 * @return boolean
	 */
	private function current_user_can_edit_admin_custom_fields() {
		return current_user_can( 'manage_lifterlms' ) && current_user_can( 'edit_users' );
	}

	/**
	 * Merge llms fields errors into the passed WP_Error
	 *
	 * @since [version]
	 * @todo Remove the fallback when minimum required WP version will be 5.6+.
	 *
	 * @param WP_Error $errors Instance of WP_Error, passed by reference.
	 * @return void
	 */
	private function merge_llms_fields_errors( &$errors ) {

		foreach ( $this->errors->get_error_codes() as $code ) {
			foreach ( $this->errors->get_error_messages( $code ) as $error_message ) {
				$errors->add(
					$code,
					sprintf(
						// Translators: %1$s = Opening strong tag; %2$s = Closing strong tag; %3$s = The error message.
						esc_html__( '%1$sError%2$s: %3$s', 'lifterlms' ),
						'<strong>',
						'</strong>',
						$error_message
					)
				);
			}

			// `WP_Error::get_all_error_data()` has been introduced in WP 5.6.0.
			$error_data = method_exists( $this->errors, 'get_all_error_data' ) ?
					$this->errors->get_all_error_data( $code ) : $this->errors->get_error_data( $code );

			foreach ( $error_data as $data ) {
				$errors->add_data( $data, $code );
			}
		}

	}

	/**
	 * Get fields to be added in the profile screen
	 *
	 * @since [version]
	 *
	 * @return array
	 */
	private function get_fields() {

		if ( ! isset( $this->fields ) ) {
			/**
			 * Fields to be added in the profile screen
			 *
			 * @since [version]
			 *
			 * @param array[] $fields Array of fields.
			 */
			$this->fields = apply_filters(
				'llms_admin_profile_fields',
				array(
					array(
						'type'           => 'text',
						'label'          => __( 'Address', 'lifterlms' ),
						'name'           => 'llms_billing_address_1',
						'id'             => 'llms_billing_address_1',
						'data_store'     => 'usermeta',
						'data_store_key' => 'llms_billing_address_1',
						'columns'        => 6,
					),
					array(
						'type'           => 'text',
						'label'          => __( 'Address line 2', 'lifterlms' ), // It's used in the error messages.
						'placeholder'    => __( 'Apartment, suite, etc...', 'lifterlms' ),
						'name'           => 'llms_billing_address_2',
						'id'             => 'llms_billing_address_2',
						'data_store'     => 'usermeta',
						'data_store_key' => 'llms_billing_address_2',
						'columns'        => 6,
					),
					array(
						'type'           => 'text',
						'label'          => __( 'City', 'lifterlms' ),
						'name'           => 'llms_billing_city',
						'id'             => 'llms_billing_city',
						'data_store'     => 'usermeta',
						'data_store_key' => 'llms_billing_city',
						'columns'        => 6,
					),
					array(
						'type'           => 'select',
						'label'          => __( 'Country', 'lifterlms' ),
						'name'           => 'llms_billing_country',
						'id'             => 'llms_billing_country',
						'data_store'     => 'usermeta',
						'data_store_key' => 'llms_billing_country',
						'options_preset' => 'countries',
						'placeholder'    => __( 'Select a Country', 'lifterlms' ),
						'columns'        => 6,
						'classes'        => 'llms-select2',
					),
					array(
						'type'           => 'select',
						'label'          => __( 'State / Region', 'lifterlms' ),
						'options_preset' => 'states',
						'placeholder'    => __( 'Select a State / Region', 'lifterlms' ),
						'name'           => 'llms_billing_state',
						'id'             => 'llms_billing_state',
						'data_store'     => 'usermeta',
						'data_store_key' => 'llms_billing_state',
						'columns'        => 6,
						'classes'        => 'llms-select2',
					),
					array(
						'type'           => 'text',
						'label'          => __( 'Postal / Zip Code', 'lifterlms' ),
						'name'           => 'llms_billing_zip',
						'id'             => 'llms_billing_zip',
						'data_store'     => 'usermeta',
						'data_store_key' => 'llms_billing_zip',
						'columns'        => 6,
					),
					array(
						'type'           => 'tel',
						'label'          => __( 'Phone Number', 'lifterlms' ),
						'name'           => 'llms_phone',
						'id'             => 'llms_phone',
						'data_store'     => 'usermeta',
						'data_store_key' => 'llms_phone',
						'columns'        => 6,
					),
				)
			);

		}

		return $this->fields;
	}

}

return new LLMS_Admin_Profile();
