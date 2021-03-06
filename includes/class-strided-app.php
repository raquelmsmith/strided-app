<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://raquelmsmith.com
 * @since      1.0.0
 *
 * @package    Strided_App
 * @subpackage Strided_App/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Strided_App
 * @subpackage Strided_App/includes
 * @author     raquelmsmith <hello@raquelmsmith.com>
 */
class Strided_App {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Strided_App_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'STRIDED_APP_VERSION' ) ) {
			$this->version = STRIDED_APP_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'strided-app';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Strided_App_Loader. Orchestrates the hooks of the plugin.
	 * - Strided_App_i18n. Defines internationalization functionality.
	 * - Strided_App_Admin. Defines all hooks for the admin area.
	 * - Strided_App_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-strided-app-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-strided-app-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-strided-app-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-strided-app-public.php';

		$this->loader = new Strided_App_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Strided_App_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Strided_App_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Strided_App_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'enqueue_block_editor_assets', $plugin_admin, 'blocks_editor_scripts' );
		$this->loader->add_action( 'enqueue_block_assets', $plugin_admin, 'strided_block_scripts' );
		$this->loader->add_action( 'init', $plugin_admin, 'action_init_register_horse_post_type' );
		$this->loader->add_action( 'init', $plugin_admin, 'action_init_register_arena_post_type' );
		$this->loader->add_action( 'init', $plugin_admin, 'action_init_register_run_post_type' );
		$this->loader->add_action( 'init', $plugin_admin, 'action_init_register_block' );
		$this->loader->add_filter( 'enter_title_here', $plugin_admin, 'filter_enter_title_here_change_title_text' );
		$this->loader->add_action( 'add_meta_boxes_horse', $plugin_admin, 'action_add_meta_boxes_horse_meta' );
		$this->loader->add_action( 'save_post_horse', $plugin_admin, 'action_save_post_horse_meta' );
		$this->loader->add_action( 'add_meta_boxes_arena', $plugin_admin, 'action_add_meta_boxes_arena_meta' );
		$this->loader->add_action( 'save_post_arena', $plugin_admin, 'action_save_post_arena_meta' );
		$this->loader->add_action( 'add_meta_boxes_run', $plugin_admin, 'action_add_meta_boxes_run_meta' );
		$this->loader->add_action( 'save_post_run', $plugin_admin, 'action_save_post_run_meta' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Strided_App_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'ninja_forms_render_options', $plugin_public, 'filter_add_horse_field_options', 10, 2 );
		$this->loader->add_action( 'strided_ninja_forms_edit_horse', $plugin_public, 'action_edit_horse_post_on_save' );
		$this->loader->add_action( 'strided_ninja_forms_edit_arena', $plugin_public, 'action_edit_arena_post_on_save' );
		$this->loader->add_action( 'strided_ninja_forms_edit_run', $plugin_public, 'action_edit_run_post_on_save' );
		$this->loader->add_filter( 'the_content', $plugin_public, 'filter_the_content_strided_post_type' );
		$this->loader->add_filter( 'the_content', $plugin_public, 'filter_the_content_show_runs' );
		$this->loader->add_filter( 'the_title', $plugin_public, 'filter_run_titles', 10, 2 );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Strided_App_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
