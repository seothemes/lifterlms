<?php
/**
 * Test Form Handler class
 *
 * @package LifterLMS/Tests
 *
 * @group forms
 * @group form_validator
 *
 * @since [version]
 * @version [version]
 */
class LLMS_Test_Form_Validator extends LLMS_UnitTestCase {

	/**
	 * Data for testing text and textarea field sanitization
	 *
	 * Each array is a numeric array with the first item being the "dirty" data and the second item
	 * being the expected result.
	 *
	 * @link https://github.com/WordPress/wordpress-develop/blob/master/tests/phpunit/tests/formatting/SanitizeTextField.php Most data adapted from WP Core tests.
	 *
	 * @return array
	 */
	protected function data_for_text_fields() {

		return array(
			array(
				'оРангутанг',
				'оРангутанг',
			),
			array(
				'one is < two',
				'one is &lt; two',
			),
			array(
				'no <span>tags</span> <em>allowed</em> here <br>',
				'no tags allowed here',
			),
			array(
				' trimmed ' ,
				'trimmed',
			),
			array(
				'No %AB octets %ab',
				'No octets',
			),
			array(
				'emails@are.okay',
				'emails@are.okay',
			),
			array(
				array(),
				'',
			),
			array(
				llms(),
				'',
			),
			array(
				false,
				'',
			),
			array(
				true,
				'1',
			),
		);

	}

	protected function get_field_arr( $type, $args = array() ) {
		return wp_parse_args( $args, array(
			'id'   => "field-{$type}-id",
			'type' => $type,
		) );
	}

	/**
	 * Setup the test case.
	 *
	 * @since [version]
	 *
	 * @return void
	 */
	public function setUp() {

		parent::setUp();
		$this->main = new LLMS_Form_Validator();

	}

	/**
	 * Test reducing a fields array down to only the required fields.
	 *
	 * @since [version]
	 *
	 * @return void
	 */
	public function test_get_required_fields() {

		$required = array(
			array(
				'name'     => 'good',
				'required' => true,
			),
			array(
				'name'     => 'good2',
				'required' => true,
			),
		);

		$optional = array(
			array(
				'name'     => 'bad',
				'required' => false,
			),
		);

		// Only has required fields in the array.
		$this->assertEquals( $required, $this->main->get_required_fields( $required ) );

		// Only has optional fields.
		$this->assertEquals( array(), $this->main->get_required_fields( $optional ) );

		// Has both required and optional.
		$this->assertEquals( $required, $this->main->get_required_fields( array_merge( $optional, $required ) ) );

	}

	/**
	 * Test validate_fields() when no user input is supplied.
	 *
	 * @since [version]
	 *
	 * @return void
	 */
	public function test_validate_fields_empty_input() {

		$res = $this->main->validate_fields( array(), array() );

		$this->assertIsWPError( $res );
		$this->assertWPErrorCodeEquals( 'llms-form-no-input', $res );
	}

	/**
	 * Test sanitize_field() for text fields / default case.
	 *
	 * @since [version]
	 *
	 * @return void
	 */
	public function test_sanitize_field_for_default() {

		$tests = $this->data_for_text_fields();

		$tests[] = array(
			"no \nnewlines",
			'no newlines',
		);
		$tests[] = array(
			"no \ttabs",
			'no tabs',
		);
		$tests[] = array(
			"internal  whitespace   removed",
			'internal whitespace removed',
		);

		foreach ( $tests as $data ) {
			$this->assertEquals( $data[1], $this->main->sanitize_field( $data[0], $this->get_field_arr( 'text' ) ) );
			$this->assertEquals( $data[1], $this->main->sanitize_field( $data[0], $this->get_field_arr( 'custom-field-type' ) ) );
		}

	}

	/**
	 * Sanitize email fields.
	 *
	 * @since [version]
	 *
	 * @return void
	 */
	public function test_sanitize_field_for_email() {

		$emails = array(
			"hello'hi@hello.com"           => "hello'hi@hello.com",
			'hello@hello.com'              => 'hello@hello.com',
			'admin@hello.net'              => '    admin@hello.net',
			'admin+hi@hello.edu'           => 'admin+hi@hello.edu',
			'hello@so.many.subdomains.org' => 'hello@so.many.subdomains.org',
			'ip@204.32.111.32'             => 'ip@204.32.111.32',
			'l@l.ms'                       => 'l@l.ms',
			''                             => 'fake',
		);

		foreach ( $emails as $clean => $dirty ) {
			$this->assertEquals( $clean, $this->main->sanitize_field( $dirty, $this->get_field_arr( 'email' ) ) );
		}

	}

	/**
	 * Test email validation.
	 *
	 * @since [version]
	 *
	 * @return void
	 */
	public function test_validate_field_for_email() {

		$emails = array(
			array( true, "hello'hi@hello.com" ),
			array( true, 'hello@hello.com' ),
			array( true, 'admin@hello.net' ),
			array( true, 'admin+hi@hello.edu' ),
			array( true, 'hello@so.many.subdomains.org' ),
			array( true, 'ip@204.32.111.32' ),
			array( true, 'l@l.ms' ),
			array( false, 'fake' ),
			array( false, 'f@k.e' ),
			array( false, ' f' ),
			array( false, 'fake.mock.com' ),
		);

		foreach ( $emails as $test ) {

			$valid = $this->main->validate_field( $test[1], $this->get_field_arr( 'email' ) );

			if ( $test[0] ) {
				$this->assertTrue( $valid );
			} else {
				$this->assertIsWPError( $valid );
			}

		}

	}

	/**
	 * Test special validation for the current password field.
	 *
	 * @since [version]
	 *
	 * @return void
	 */
	public function test_validate_field_for_password_current() {

		// Not logged in.
		$no_user = $this->main->validate_field( 'password', $this->get_field_arr( 'password', array( 'id' => 'password_current' ) ) );
		$this->assertIsWPError( $no_user );
		$this->assertWPErrorCodeEquals( 'llms-form-field-invalid-no-user', $no_user );

		wp_set_current_user( $this->factory->user->create( array( 'user_pass' => 'password' ) ) );

		// Invalid password.
		$invalid = $this->main->validate_field( 'fake', $this->get_field_arr( 'password', array( 'id' => 'password_current' ) ) );
		$this->assertIsWPError( $invalid );
		$this->assertWPErrorCodeEquals( 'llms-form-field-invalid', $invalid );

		// Valid.
		$valid = $this->main->validate_field( 'password', $this->get_field_arr( 'password', array( 'id' => 'password_current' ) ) );
		$this->assertTrue( $valid );

	}

	/**
	 * Test special validation for user emails. They must be unique.
	 *
	 * @since [version]
	 *
	 * @return void
	 */
	public function test_validate_field_for_user_email() {

		$email = sprintf( 'mock+%s@mock.tld', uniqid() );

		// Valid.
		$valid = $this->main->validate_field( $email, $this->get_field_arr( 'email', array( 'id' => 'user_email' ) ) );
		$this->assertTrue( $valid );

		// Not unique.
		$this->factory->user->create( array( 'user_email' => $email ) );
		$exists = $this->main->validate_field( $email, $this->get_field_arr( 'email', array( 'id' => 'user_email' ) ) );
		$this->assertIsWPError( $exists );
		$this->assertWPErrorCodeEquals( 'llms-form-field-not-unique', $exists );

	}

	/**
	 * Test special validation for the username field.
	 *
	 * @since [version]
	 *
	 * @return void
	 */
	public function test_validate_field_for_user_login() {

		// Banned.
		$banned = $this->main->validate_field( 'admin', $this->get_field_arr( 'text', array( 'id' => 'user_login' ) ) );
		$this->assertIsWPError( $banned );
		$this->assertWPErrorCodeEquals( 'llms-form-field-invalid', $banned );

		// Not valid.
		$invalid = $this->main->validate_field( '   +-!', $this->get_field_arr( 'text', array( 'id' => 'user_login' ) ) );
		$this->assertIsWPError( $invalid );
		$this->assertWPErrorCodeEquals( 'llms-form-field-invalid', $invalid );

		$login = sprintf( 'mock-%s', uniqid() );

		// Valid.
		$valid = $this->main->validate_field( $login, $this->get_field_arr( 'text', array( 'id' => 'user_login' ) ) );
		$this->assertTrue( $valid );

		// Not unique.
		$this->factory->user->create( array( 'user_login' => $login ) );
		$exists = $this->main->validate_field( $login, $this->get_field_arr( 'text', array( 'id' => 'user_login' ) ) );
		$this->assertIsWPError( $exists );
		$this->assertWPErrorCodeEquals( 'llms-form-field-not-unique', $exists );

	}

	/**
	 * Sanitize telephone fields.
	 *
	 * @since [version]
	 *
	 * @return void
	 */
	public function test_sanitize_field_for_tel() {

		$tels = array(
			'+00 000 000 0000'   => '+00 000 000 0000',
			'+00-000-000-0000'   => '+00-000-000-0000',
			'(000) 000 0000'     => '(000) 000 0000',
			'+00.000.000.0000'   => '+00.000.000.0000',
			'+00 (000) 000 0000' => '+00 (000) 000 0000',
			'000 000 0000 #000'  => '000 000 0000 #000',
			'+00'                => '+00 aaa bbb cccc',
			''                   => 'fake',
		);

		foreach ( $tels as $clean => $dirty ) {
			$this->assertEquals( $clean, $this->main->sanitize_field( $dirty, $this->get_field_arr( 'tel' ) ) );
		}

	}

	/**
	 * Test telephone validation.
	 *
	 * @since [version]
	 *
	 * @return void
	 */
	public function test_validate_field_for_tel() {

		$emails = array(
			array( true, '+00 000 000 0000' ),
			array( true, '+00-000-000-0000' ),
			array( true, '(000) 000 0000' ),
			array( true, '+00.000.000.0000' ),
			array( true, '+00 (000) 000 0000' ),
			array( true, '000 000 0000 #000' ),
			array( false, '+00 aaa bbb cccc' ),
			array( false, 'fake' ),
		);

		foreach ( $emails as $test ) {

			$valid = $this->main->validate_field( $test[1], $this->get_field_arr( 'tel' ) );

			if ( $test[0] ) {
				$this->assertTrue( $valid );
			} else {
				$this->assertIsWPError( $valid );
			}

		}

	}

	/**
	 * Test sanitize_field() for textareas
	 *
	 * We don't need super thorough tests here as we're using a WP Core function.
	 *
	 * @since [version]
	 *
	 * @return void
	 */
	public function test_sanitize_field_for_textarea() {

		$tests = $this->data_for_text_fields();

		$tests[] = array(
			"newlines \nokay",
			"newlines \nokay",
		);
		$tests[] = array(
			"tabs \nokay too",
			"tabs \nokay too",
		);
		$tests[] = array(
			"internal  whitespace   okay",
			"internal  whitespace   okay",
		);

		foreach ( $tests as $data ) {
			$this->assertEquals( $data[1], $this->main->sanitize_field( $data[0], $this->get_field_arr( 'textarea' ) ) );
		}

	}

	/**
	 * Test sanitize_field() for URL fields.
	 *
	 * We don't need super thorough tests here as we're using a WP Core function.
	 *
	 * @since [version]
	 *
	 * @return void
	 */
	public function test_sanitize_field_for_url() {

		$tests = array(
			array(
				'https://example.tld/',
				'https://example.tld/',
			),
			array(
				'http://www.example.tld',
				'http://www.example.tld',
			),
			array(
				'https://example.tld/path/to/something',
				'https://example.tld/path/to/something',
			),
			array(
				'https://example.tld?qs=yes&more=1',
				'https://example.tld?qs=yes&more=1',
			),
			array(
				'https://example.tld/with space',
				'https://example.tld/with%20space',
			),
			array(
				'data:text/plain;base64,SGVsbG8sIFdvcmxkIQ%3D%3D',
				'',
			),
			array(
				'data:text/plain;base64,SGVsbG8sIFdvcmxkIQ%3D%3D',
				'',
			),
			array(
				'https://example.tld/?qs=whatever+<script>alert(1)</script>',
				'https://example.tld/?qs=whatever+scriptalert(1)/script',
			),
		);

		foreach ( $tests as $data ) {
			$this->assertEquals( $data[1], $this->main->sanitize_field( $data[0], $this->get_field_arr( 'url' ) ) );
		}

	}

	/**
	 * Test validate_field() for a URL field.
	 *
	 * @since [version]
	 *
	 * @return void
	 */
	public function test_validate_field_for_url() {

		$tests = array(
			array( false, 'notaurl' ),
			array( false, 'test.php' ),
			array( false, 'example.tld' ),
			array( true, 'https://example.tld' ),
		);

		foreach ( $tests as $test ) {

			$valid = $this->main->validate_field( $test[1], $this->get_field_arr( 'url' ) );

			if ( $test[0] ) {
				$this->assertTrue( $valid );
			} else {
				$this->assertIsWPError( $valid );
			}

		}

	}

	/**
	 * Sanitize number fields.
	 *
	 * @since [version]
	 *
	 * @return void
	 */
	public function test_sanitize_field_for_number() {

		$numbers = array(
			'1'        => '1',
			'100'      => '100',
			'1.00'     => '1.00',
			'1,00'     => '1,00',
			'1,000'    => '1,000',
			'1.000'    => '1.000',
			'1,000.00' => '1,000.00',
			'1.000,00' => '1.000,00',
			'2'        => ' fake 2 mock',
		);

		foreach ( $numbers as $clean => $dirty ) {
			$this->assertEquals( $clean, $this->main->sanitize_field( $dirty, $this->get_field_arr( 'number' ) ) );
		}

	}

	/**
	 * Test number field validation.
	 *
	 * @since [version]
	 *
	 * @return void
	 */
	public function test_validate_field_for_number() {

		$field = $this->get_field_arr( 'number', array( 'name' => 'number_field' ) );

		$emails = array(
			array( true, '1' ),
			array( true, '-1' ),
			array( true, '+1' ),
			array( true, '100' ),
			array( true, '1.00' ),
			array( true, '1,00' ),
			array( true, '1,000' ),
			array( true, '1.000' ),
			array( true, '1,000.00' ),
			array( true, '1.000,00' ),
			array( false, ' fake 2 mock' ),
		);

		foreach ( $emails as $test ) {

			$valid = $this->main->validate_field( $test[1], $field );

			if ( $test[0] ) {
				$this->assertTrue( $valid );
			} else {
				$this->assertIsWPError( $valid );
			}

		}

		$field['attributes']['min'] = '25';
		$field['attributes']['max'] = '500';

		// Number too small.
		$this->assertIsWPError( $this->main->validate_field( '10', $field ) );
		$this->assertIsWPError( $this->main->validate_field( '10.50', $field ) );
		$this->assertIsWPError( $this->main->validate_field( '24.99', $field ) );
		$this->assertIsWPError( $this->main->validate_field( '-500', $field ) );

		// Number too large.
		$this->assertIsWPError( $this->main->validate_field( '500.01', $field ) );
		$this->assertIsWPError( $this->main->validate_field( '1,000', $field ) );
		$this->assertIsWPError( $this->main->validate_field( '+99999', $field ) );

	}

	/**
	 * Test special voucher field validation.
	 *
	 * @since [version]
	 *
	 * @return void
	 */
	public function test_validate_field_for_voucher() {

		$field = $this->get_field_arr( 'text', array( 'id' => 'llms_voucher' ) );

		// Invalid code.
		$res = $this->main->validate_field( 'invalid-code', $field );
		$this->assertIsWPError( $res );
		$this->assertWPErrorMessageEquals( 'Voucher code "invalid-code" could not be found.', $res );

		// Valid code.
		$voucher = $this->get_mock_voucher( 1 );
		$code    = $voucher->get_voucher_codes()[0]->code;
		$res = $this->main->validate_field( $code, $field );
		$this->assertTrue( $res );

		// Use the voucher.
		$voucher->use_voucher( $code, 123 );

		// Valid code without any remaining redemptions.
		$res = $this->main->validate_field( $code, $field );
		$this->assertIsWPError( $res );
		$this->assertWPErrorMessageEquals( sprintf( 'Voucher code "%s" has already been redeemed the maximum number of times.', $code ), $res );

	}

	/**
	 * Test checking matching fields.
	 *
	 * @since [version]
	 *
	 * @return void
	 */
	public function test_validate_matching_fields() {

		$posted = array(
			'email' => 'l@l.ms',
			'email_confirm' => 'l@l.ms',
		);

		$fields = array(
			array(
				'id'    => 'email',
				'name'  => 'email',
				'match' => 'email_confirm',
			),
			array(
				'id'    => 'email_confirm',
				'name'  => 'email_confirm',
				'match' => 'email',
			),
		);

		$this->assertTrue( $this->main->validate_matching_fields( $posted, $fields ) );

	}

	/**
	 * Test checking matching fields when the matching field doesn't exist in the form.
	 *
	 * @since [version]
	 *
	 * @return void
	 */
	public function test_validate_matching_fields_err_missing_match_definition() {

		$posted = array(
			'email' => 'l@l.ms',
		);

		$fields = array(
			array(
				'id'    => 'email',
				'name'  => 'email',
				'match' => 'email_confirm',
			),
		);

		$this->assertTrue( $this->main->validate_matching_fields( $posted, $fields ) );

	}

	/**
	 * Test checking matchind fields when user data is mismatched.
	 *
	 * @since [version]
	 *
	 * @return void
	 */
	public function test_validate_matching_fields_err_missing_match() {

		$posted = array(
			'email' => 'l@l.ms',
		);

		$fields = array(
			array(
				'id'    => 'email',
				'name'  => 'email',
				'match' => 'email_confirm',
			),
			array(
				'id'    => 'email_confirm',
				'name'  => 'email_confirm',
				'match' => 'email',
			),
		);

		$valid = $this->main->validate_matching_fields( $posted, $fields );
		$this->assertIsWPError( $valid );
		$this->assertWPErrorCodeEquals( 'llms-form-field-not-matched', $valid );

		// Should only have a single error message, not one error for each message.
		$this->assertEquals( 1, count( $valid->get_error_messages( 'llms-form-field-not-matched' ) ) );

	}

	/**
	 * Test validate_required_fields()
	 *
	 * @since [version]
	 *
	 * @return void
	 */
	public function test_validate_required_fields_exist() {

		$posted = array(
			'email'    => 'fake@mock.com',
			'password' => '1234',
		);

		$fields = array(
			array(
				'label'    => __( 'Email', 'lifterlms' ),
				'name'     => 'email',
				'required' => true,
			),
			array(
				'label'    => __( 'Password', 'lifterlms' ),
				'name'     => 'password',
				'required' => true,
			),
			array(
				'label'    => __( 'Name', 'lifterlms' ),
				'name'     => 'name',
				'required' => false,
			),
		);

		$this->assertTrue( $this->main->validate_required_fields( $posted, $fields ) );

	}

	/**
	 * Test validity of form based on presence of all required fields.
	 *
	 * @since [version]
	 *
	 * @return void
	 */
	public function test_validate_required_fields_missing_fields() {

		$posted = array();

		$fields = array(
			array(
				'label'    => __( 'Email', 'lifterlms' ),
				'name'     => 'email',
				'required' => true,
			),
			array(
				'label'    => __( 'Password', 'lifterlms' ),
				'name'     => 'password',
				'required' => true,
			),
			array(
				'label'    => __( 'Name', 'lifterlms' ),
				'name'     => 'name',
				'required' => false,
			),
		);

		// Missing both required fields,
		$valid = $this->main->validate_required_fields( $posted, $fields );
		$this->assertIsWPError( $valid );
		$this->assertWPErrorCodeEquals( 'llms-form-missing-required', $valid );
		$this->assertEquals( 2, count( $valid->errors['llms-form-missing-required'] ) );
		$this->assertEquals( 'Email is a required field.', $valid->errors['llms-form-missing-required'][0] );
		$this->assertEquals( 'Password is a required field.', $valid->errors['llms-form-missing-required'][1] );

		// Only missing password.
		$posted['email'] = 'fake@mock.com';
		$valid = $this->main->validate_required_fields( $posted, $fields );
		$this->assertIsWPError( $valid );
		$this->assertWPErrorCodeEquals( 'llms-form-missing-required', $valid );
		$this->assertEquals( 1, count( $valid->errors['llms-form-missing-required'] ) );
		$this->assertEquals( 'Password is a required field.', $valid->errors['llms-form-missing-required'][0] );

	}

}