<?php
/**
 * LifterLMS Shortcodes Blocks
 *
 * @since   7.0.1
 * @package LifterLMS/Classes/Shortcodes
 */

defined( 'ABSPATH' ) || exit;

/**
 * LLMS_Shortcodes_Blocks
 *
 * @since 7.0.1
 */
class LLMS_Shortcodes_Blocks {

	/**
	 * Available shortcode blocks.
	 */
	private array $shortcodes = array(
		'access-plan-button',
		'checkout',
		'courses',
		'course-author',
		'course-continue',
		'course-meta-info',
		'course-outline',
		'course-prerequisites',
		'course-reviews',
		'course-syllabus',
		'login',
		'memberships',
		'my-account',
		'my-achievements',
		'registration',
	);

	/**
	 * Constructor.
	 *
	 * @since 7.0.1
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_blocks' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_editor_styles' ) );
		add_filter( 'block_categories', array( $this, 'register_block_category' ), 11 );
		add_filter( 'llms_hide_registration_form', array( $this, 'show_form_preview' ) );
		add_filter( 'llms_hide_login_form', array( $this, 'show_form_preview' ) );
	}

	/**
	 * Registers shortcode blocks.
	 *
	 * @since 7.0.1
	 *
	 * @return void
	 */
	public function register_blocks(): void {
		foreach ( $this->shortcodes as $shortcode ) {
			register_block_type( LLMS_PLUGIN_DIR . "blocks/$shortcode" );
		}
	}

	/**
	 * Loads front end CSS in the editor.
	 *
	 * @since 7.0.1
	 *
	 * @return void
	 */
	public function enqueue_block_editor_assets(): void {
		wp_enqueue_style(
			'lifterlms-styles',
			LLMS()->plugin_url() . '/assets/css/lifterlms.min.css',
			array(),
			filemtime( LLMS()->plugin_path() . '/assets/css/lifterlms.min.css' )
		);

		// Add access plan data to script (not available in REST).
		wp_localize_script(
			'llms-blocks-editor',
			'llmsShortcodeBlocks',
			array(
				'accessPlans' => $this->get_access_plans(),
			)
		);
	}

	/**
	 * Enqueues editor styles.
	 *
	 * @since 7.0.1
	 *
	 * @return void
	 */
	public function enqueue_editor_styles(): void {
		if ( ! $this->is_block_editor() ) {
			return;
		}

		wp_enqueue_style(
			'llms-editor',
			LLMS()->plugin_url() . '/assets/css/editor.min.css',
			array(),
			filemtime( LLMS()->plugin_path() . '/assets/css/editor.min.css' )
		);
	}

	/**
	 * Registers the LifterLMS block category.
	 *
	 * @since 7.0.1
	 *
	 * @param array $categories Array of block categories.
	 *
	 * @return array
	 */
	public function register_block_category( array $categories ): array {
		return array_merge(
			$categories,
			array(
				array(
					'slug'  => 'lifterlms',
					'title' => __( 'LifterLMS', 'lifterlms' ),
				),
			)
		);
	}

	/**
	 * Returns an array of access plans (not available in REST).
	 *
	 * @since 7.0.1
	 *
	 * @return array
	 */
	private function get_access_plans(): array {
		$query = new WP_Query(
			array(
				'post_type'      => 'llms_access_plan',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
			)
		);

		return array_map(
			function ( $post ) {
				return array(
					'value' => $post->ID,
					'label' => $post->post_title,
				);
			},
			$query->posts
		);
	}

	/**
	 * Shows the registration and login form in editor preview.
	 *
	 * @since 7.0.1
	 *
	 * @param bool $hide Whether to hide the registration form.
	 *
	 * @return bool
	 */
	public function show_form_preview( bool $hide ): bool {
		if ( ! defined( 'REST_REQUEST' ) || ! is_user_logged_in() ) {
			return $hide;
		}

		global $wp;

		if ( ! $wp instanceof WP || empty( $wp->query_vars['rest_route'] ) ) {
			return $hide;
		}

		$route = $wp->query_vars['rest_route'];

		if ( false !== strpos( $route, '/block-renderer/' ) ) {
			$hide = false;
		}

		return $hide;
	}

	/**
	 * Checks if the current page is a block editor page.
	 *
	 * @since 7.0.1
	 *
	 * @return bool
	 */
	private function is_block_editor(): bool {
		if ( function_exists( 'is_gutenberg_page' ) && is_gutenberg_page() ) {
			return true;
		}

		$current_screen = get_current_screen();

		if ( method_exists( $current_screen, 'is_block_editor' ) && $current_screen->is_block_editor() ) {
			return true;
		}

		return false;
	}
}

return new LLMS_Shortcodes_Blocks();
