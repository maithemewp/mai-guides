<?php

/**
 * Plugin Name:     Mai Guides
 * Plugin URI:      https://maitheme.com
 * Description:     Create SEO friendly guide posts that feature an ordered list of hand-picked posts.
 * Version:         0.1.0
 *
 * Author:          BizBudding, Mike Hemberger
 * Author URI:      https://bizbudding.com
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Main Mai_Guides Class.
 *
 * @since 0.1.0
 */
final class Mai_Guides {

	/**
	 * @var   Mai_Guides The one true Mai_Guides
	 * @since 0.1.0
	 */
	private static $instance;

	/**
	 * Main Mai_Guides Instance.
	 *
	 * Insures that only one instance of Mai_Guides exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since   0.1.0
	 * @static  var array $instance
	 * @uses    Mai_Guides::setup_constants() Setup the constants needed.
	 * @uses    Mai_Guides::includes() Include the required files.
	 * @uses    Mai_Guides::hooks() Activate, deactivate, etc.
	 * @see     Mai_Guides()
	 * @return  object | Mai_Guides The one true Mai_Guides
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			// Setup the setup.
			self::$instance = new Mai_Guides;
			// Methods.
			self::$instance->setup_constants();
			self::$instance->includes();
			self::$instance->hooks();
		}
		return self::$instance;
	}

	/**
	 * Throw error on object clone.
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since   0.1.0
	 * @access  protected
	 * @return  void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'mai-guides' ), '1.0' );
	}

	/**
	 * Disable unserializing of the class.
	 *
	 * @since   0.1.0
	 * @access  protected
	 * @return  void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'mai-guides' ), '1.0' );
	}

	/**
	 * Setup plugin constants.
	 *
	 * @access  private
	 * @since   0.1.0
	 * @return  void
	 */
	private function setup_constants() {

		// Plugin version.
		if ( ! defined( 'MAI_GUIDES_VERSION' ) ) {
			define( 'MAI_GUIDES_VERSION', '0.1.0' );
		}

		// Plugin Folder Path.
		if ( ! defined( 'MAI_GUIDES_PLUGIN_DIR' ) ) {
			define( 'MAI_GUIDES_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		}

		// Plugin Includes Path.
		if ( ! defined( 'MAI_GUIDES_INCLUDES_DIR' ) ) {
			define( 'MAI_GUIDES_INCLUDES_DIR', MAI_GUIDES_PLUGIN_DIR . 'includes/' );
		}

		// Plugin Folder URL.
		if ( ! defined( 'MAI_GUIDES_PLUGIN_URL' ) ) {
			define( 'MAI_GUIDES_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}

		// Plugin Root File.
		if ( ! defined( 'MAI_GUIDES_PLUGIN_FILE' ) ) {
			define( 'MAI_GUIDES_PLUGIN_FILE', __FILE__ );
		}

		// Plugin Base Name
		if ( ! defined( 'MAI_GUIDES_BASENAME' ) ) {
			define( 'MAI_GUIDES_BASENAME', dirname( plugin_basename( __FILE__ ) ) );
		}

	}

	/**
	 * Include required files.
	 *
	 * @access  private
	 * @since   0.1.0
	 * @return  void
	 */
	private function includes() {
		// Include vendor libraries.
		require_once __DIR__ . '/vendor/autoload.php';
		// Includes.
		foreach ( glob( MAI_GUIDES_INCLUDES_DIR . '*.php' ) as $file ) { include $file; }
	}

	/**
	 * Run the hooks.
	 *
	 * @since   0.1.0
	 * @return  void
	 */
	public function hooks() {

		add_action( 'admin_init', array( $this, 'updater' ) );
		add_action( 'init',       array( $this, 'register_content_types' ) );

		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
	}

	/**
	 * Setup the updater.
	 *
	 * composer require yahnis-elsts/plugin-update-checker
	 *
	 * @uses    https://github.com/YahnisElsts/plugin-update-checker/
	 *
	 * @return  void
	 */
	public function updater() {

		// Bail if current user cannot manage plugins.
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		// Bail if plugin updater is not loaded.
		if ( ! class_exists( 'Puc_v4_Factory' ) ) {
			return;
		}

		// Setup the updater.
		$updater = Puc_v4_Factory::buildUpdateChecker( 'https://github.com/maithemewp/mai-guides/', __FILE__, 'mai-guides' );
	}

	/**
	 * Register content types.
	 *
	 * @return  void
	 */
	public function register_content_types() {

		// Guides.
		register_post_type( 'guide', apply_filters( 'mai_guide_args', array(
			'exclude_from_search' => false,
			'has_archive'         => true,
			'hierarchical'        => true,
			'labels'              => array(
				'name'               => _x( 'Guides',  'Guide general name',            'mai-guide' ),
				'singular_name'      => _x( 'Guide',   'Guide singular name',           'mai-guide' ),
				'menu_name'          => _x( 'Guides',  'Guide admin menu',              'mai-guide' ),
				'name_admin_bar'     => _x( 'Guide',   'Guide add new on admin bar',    'mai-guide' ),
				'add_new'            => _x( 'Add New', 'Guide',                         'mai-guide' ),
				'add_new_item'       => __( 'Add New Guide',                            'mai-guide' ),
				'new_item'           => __( 'New Guide',                                'mai-guide' ),
				'edit_item'          => __( 'Edit Guide',                               'mai-guide' ),
				'view_item'          => __( 'View Guide',                               'mai-guide' ),
				'all_items'          => __( 'All Guides',                               'mai-guide' ),
				'search_items'       => __( 'Search Guides',                            'mai-guide' ),
				'parent_item_colon'  => __( 'Parent Guides:',                           'mai-guide' ),
				'not_found'          => __( 'No Guides found.',                         'mai-guide' ),
				'not_found_in_trash' => __( 'No Guides found in Trash.',                'mai-guide' )
			),
			'menu_icon'          => 'dashicons-list-view',
			'public'             => true,
			'publicly_queryable' => true,
			'show_in_menu'       => true,
			'show_in_nav_menus'  => true,
			'show_ui'            => true,
			'rewrite'            => array( 'slug' => 'guides', 'with_front' => false ),
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'genesis-cpt-archives-settings', 'genesis-adjacent-entry-nav' ),
		) ) );

	}

	/**
	 * Plugin activation.
	 *
	 * @return  void
	 */
	public function activate() {
		$this->register_content_types();
		flush_rewrite_rules();
	}

}

/**
 * The main function for that returns Mai_Guides
 *
 * The main function responsible for returning the one true Mai_Guides
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $plugin = Mai_Guides(); ?>
 *
 * @since 0.1.0
 *
 * @return object|Mai_Guides The one true Mai_Guides Instance.
 */
function Mai_Guides() {
	return Mai_Guides::instance();
}

// Get Mai_Guides Running.
Mai_Guides();
