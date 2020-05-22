<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       moosend.com
 * @since      1.0.0
 *
 * @package    Moosend_For_Wp
 * @subpackage Moosend_For_Wp/includes
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
 * @package    Moosend_For_Wp
 * @subpackage Moosend_For_Wp/includes
 * @author     Theo Nastos <theo@moosend.com>
 */
class Moosend_For_Wp {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Moosend_For_Wp_Loader    $loader    Maintains and registers all hooks for the plugin.
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
	protected $api_key;

	protected $endpoint;

	public function __construct() {

		$this->plugin_name = 'moosend-for-wp';
		$this->version = '1.0.0';
		$options = get_option('moosend-for-wp-general-settings-section');
		$this->api_key = $options['api_key'];

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/*
	* @since    1.0.0
	* @access   private
	*/
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-moosend-for-wp-loader.php';
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-moosend-for-wp-list-table2.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-moosend-for-wp-subscription-form.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'environment.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-moosend-for-wp-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-moosend-for-wp-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-moosend-for-wp-public.php';
	
		
		$this->loader = new Moosend_For_Wp_Loader();
		$this->endpoint = $GLOBALS['endpoint'];

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Moosend_For_Wp_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Moosend_For_Wp_i18n();

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
		//Check hooks https://codex.wordpress.org/Plugin_API/Action_Reference/admin_init
		$plugin_admin = new Moosend_For_Wp_Admin( $this->get_plugin_name(), $this->get_version(), $this->get_api_key(), $this->get_endpoint());

		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

		$this->loader->add_action('admin_init', $plugin_admin, 'options_update');
		$this->loader->add_action('admin_init', $plugin_admin, 'add_theme_url');

		//Ajax Callbacks
		$this->loader->add_action('wp_ajax_form_params_json', $plugin_admin, 'form_params_json_callback');
		$this->loader->add_action('wp_ajax_nopriv_form_params_json', $plugin_admin, 'form_params_json_callback');

		$this->loader->add_action('wp_ajax_edit_form_params_json', $plugin_admin, 'edit_form_params_json_callback');
		$this->loader->add_action('wp_ajax_nopriv_edit_form_params_json', $plugin_admin, 'edit_form_params_json_callback');

		$this->loader->add_action('wp_ajax_delete_forms', $plugin_admin, 'delete_forms_callback');
		$this->loader->add_action('wp_ajax_nopriv_delete_forms', $plugin_admin, 'delete_forms_callback');

		$this->loader->add_action('admin_menu', $plugin_admin, 'add_plugin_admin_menu');
		
		$this->loader->add_shortcode('ms-form', $plugin_admin, 'register_shortcodes');

		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ).$this->plugin_name . '.php' );
		$this->loader->add_filter('plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links');

	}

	/**
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Moosend_For_Wp_Public( $this->get_plugin_name(), $this->get_version(), $this->get_api_key());

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action('wp_ajax_get_form_details', $plugin_public, 'get_form_details_callback');
		$this->loader->add_action('wp_ajax_nopriv_get_form_details', $plugin_public, 'get_form_details_callback');

		$this->loader->add_action('wp_ajax_get_client_ip_address', $plugin_public, 'get_client_ip_address_callback');
		$this->loader->add_action('wp_ajax_nopriv_get_client_ip_address', $plugin_public, 'get_client_ip_address_callback');

		$this->loader->add_action('wp_ajax_subscribe_member', $plugin_public, 'subscribe_member_callback');
		$this->loader->add_action('wp_ajax_nopriv_subscribe_member', $plugin_public, 'subscribe_member_callback');
	}

	/**
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 *
	 * @since     1.0.0
	 * @return    Moosend_For_Wp_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	public function get_api_key() {
		return $this->api_key;
	}

	public function get_endpoint() {
		return $this->endpoint;
	}

	public function set_api_key($api_key) { //TO DO - Use function in general settings
		$this->api_key = $api_key;
	}
	
}
