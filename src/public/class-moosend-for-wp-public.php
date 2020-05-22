<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       moosend.com
 * @since      1.0.0
 *
 * @package    Moosend_For_Wp
 * @subpackage Moosend_For_Wp/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Moosend_For_Wp
 * @subpackage Moosend_For_Wp/public
 * @author     Theo Nastos <theo@moosend.com>
 */

require_once(dirname(__DIR__).'/environment.php');

class Moosend_For_Wp_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */

	private $api_key;

	public function __construct( $plugin_name, $version, $api_key) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->api_key = $api_key;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
 		wp_enqueue_style( 'jquery-modal', plugin_dir_url( __FILE__ ).'css/jquery.modal.css', array(), $this->version, 'all');
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/moosend-for-wp-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_register_script('jquery-modal', plugin_dir_url (__FILE__).'js/jquery.modal.js', array(), $this->version);
		wp_register_script('jquery-redirect', plugin_dir_url (__FILE__).'dependencies/jquery-redirect/jquery.redirect.js', array(), $this->version);
		wp_enqueue_script('alingment-sets', plugin_dir_url (__FILE__).'js/alignment-sets.js', false, $this->version, false);
		wp_localize_script('alingment-sets', 'php_vars', 
				array('home_url' => get_home_url()));
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/moosend-for-wp-public.js', array( 'jquery', 'jquery-modal', 'jquery-redirect'), $this->version, false );
		wp_localize_script($this->plugin_name, 'php_vars', 
				array('ajax_url' => admin_url('admin-ajax.php')));
	}

	public function get_form_details_callback() {
		global $wpdb;
		$forms = get_option('forms');
		$form_id = $_POST['params'];
		$form = $forms[$form_id];

		$response = array (
			"modalParams" => $form->popup_settings,
			"formList" => $form->selected_list,
			"redirectUrl" => $form->after_subscription,
			"newTab" => $form->new_tab
		);
		
		echo json_encode($response);

		wp_die();
	}

	public function get_client_ip_address_callback() {
		global $wpdb;
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			echo json_encode($_SERVER['HTTP_CLIENT_IP']);
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			echo json_encode($_SERVER['HTTP_X_FORWARDED_FOR']);
		} else {
			echo json_encode($_SERVER['REMOTE_ADDR']);
		}

		wp_die();
	}

	public function subscribe_member_callback() {
		global $wpdb;
		
		$params = $_POST['params'];

		$endpoint = $GLOBALS['endpoint'] .'members/subscribe/';

		$response = wp_remote_post(
			$endpoint, 
			array(
				'headers' => array('X-ApiKey' => $this->api_key),
				'body' => json_encode($params)
			));

		$response_code = wp_remote_retrieve_response_code($response);
		
		if(is_wp_error( $response )) {
			return false; // Bail early
		}

		$body = wp_remote_retrieve_body($response);
		$data = json_decode($body);

		echo $body;

		wp_die();
	}
}
