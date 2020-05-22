<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       moosend.com
 * @since      1.0.0
 *
 * @package    Moosend_For_Wp
 * @subpackage Moosend_For_Wp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Moosend_For_Wp
 * @subpackage Moosend_For_Wp/admin
 * @author     Moosend <theo@moosend.com>
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once(dirname(__DIR__).'/environment.php');


class Moosend_For_Wp_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	private $api_key;

	private $endpoint;

	private $subpage_map;

	public function __construct( $plugin_name, $version, $api_key, $endpoint) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->api_key = $api_key;
		$this->endpoint = $endpoint;
		$this->subpage_map = array(
			'main-page' => 'toplevel_page_moosend-for-wp-general',
			'add-page' => 'moosend-for-wp_page_moosend-for-wp-add-form',
			'edit-page' => 'admin_page_moosend-for-wp-edit-form',
			'all-page' => 'moosend-for-wp_page_moosend-for-wp-all-forms'
		);
	}

	public function enqueue_styles() {

		$current_screen = get_current_screen()->id;

		foreach($this->subpage_map as $key => $value){
			if($value == $current_screen){
				wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ).'css/moosend-for-wp-admin.css', array(), $this->version, 'all');
				wp_enqueue_style( 'jquery-ui-css', plugin_dir_url( __FILE__ ).'dependencies/jquery-ui-dialog/jquery-ui-dialog.css', array(), $this->version, 'all');
				wp_enqueue_style( 'simple-grid', plugin_dir_url( __FILE__ ).'dependencies/simple-grid/simplegrid.css', array(), $this->version, 'all');
			}
		}
		
		if ($current_screen == $this->subpage_map['add-page'] || $current_screen == $this->subpage_map['edit-page'])
		{	
			wp_enqueue_style('jquery-responsive-tabs-css', plugin_dir_url (__FILE__).'dependencies/jquery-responsive-tabs/responsive-tabs.css', array(), $this->version, 'all'); 
			wp_enqueue_style('jquery-responsive-tabs-style-css', plugin_dir_url (__FILE__).'dependencies/jquery-responsive-tabs/responsive-tabs-style.css', array(), $this->version, 'all'); 
			wp_enqueue_style( 'ms4wp-add-css', plugin_dir_url( __FILE__ ).'css/ms4wp-add.css', array(), $this->version, 'all');
			wp_enqueue_style('wp-color-picker');
			wp_enqueue_style( 'jquery-steps-css', plugin_dir_url( __FILE__ ).'dependencies/jquery-steps/jquery-steps.min.css', array(), $this->version, 'all');
		}
		
		if ($current_screen == $this->subpage_map['main-page']){
			wp_enqueue_style( 'ms4wp-general-css', plugin_dir_url( __FILE__ ).'css/ms4wp-general.css', array(), $this->version, 'all');
		}

		if ($current_screen == $this->subpage_map['all-page']){
			wp_enqueue_style( 'ms4wp-all-css', plugin_dir_url( __FILE__ ).'css/ms4wp-all.css', array(), $this->version, 'all');
		}
	}

	public function enqueue_scripts() {

		$current_screen = get_current_screen()->id;

		foreach($this->subpage_map as $key => $value){
			if($value == $current_screen){
				wp_register_script('jquery-validate', plugin_dir_url (__FILE__).'dependencies/jquery-validate/jquery.validate.min.js', array(), $this->version); 
			}
		}

		if ($current_screen == $this->subpage_map['add-page'] || $current_screen == $this->subpage_map['edit-page']) 
		{
			wp_register_script('jquery-responsive-tabs', plugin_dir_url (__FILE__).'dependencies/jquery-responsive-tabs/jquery.responsiveTabs.min.js', array(), $this->version); 
			wp_register_script('jquery-steps', plugin_dir_url (__FILE__).'dependencies/jquery-steps/jquery.steps.min.js', array(), $this->version); 
			wp_enqueue_script('css-hack', plugin_dir_url (__FILE__).'js/css-hack.js', false, $this->version, false);
			wp_enqueue_script( $this->plugin_name.'-add', plugin_dir_url( __FILE__ ).'js/ms4wp-add.js', array('jquery','jquery-responsive-tabs' ,'jquery-steps', 'jquery-ui-dialog', 'jquery-validate', 'wp-color-picker'), $this->version, false);
			wp_localize_script($this->plugin_name.'-add', 'php_vars',
				array('api_key' => $this->api_key, 
					'ajax_url' => admin_url('admin-ajax.php'),
					'endpoint' => $this->endpoint,
					'redirect' => admin_url('admin.php?page=' . $this->plugin_name.'-all-forms')
					));
		}

		if ($current_screen == $this->subpage_map['main-page']){
			wp_enqueue_script( $this->plugin_name.'-general', plugin_dir_url( __FILE__ ).'js/ms4wp-general.js', array('jquery',  'jquery-ui-dialog', 'jquery-validate'), $this->version, false);
			wp_localize_script($this->plugin_name.'-general', 'php_vars', 
				array('registrationEndpoint' => $GLOBALS['registration_endpoint'],
					'api_key' => $this->api_key,
					'ajax_url' => admin_url('admin-ajax.php')
					));
		}

		if ($current_screen == $this->subpage_map['all-page']){
			wp_enqueue_script( $this->plugin_name.'-all-forms', plugin_dir_url( __FILE__ ).'js/ms4wp-all.js', array('jquery'), $this->version, false);
		}
	}

	/* Create The Admin Menu For The Plugin */

	public function add_plugin_admin_menu() {
		add_menu_page( 'Moosend For WordPress', 'Moosend For WP', 'manage_options', $this->plugin_name.'-general', array($this, 'display_plugin_setup_page'), plugin_dir_url( __FILE__ ).'img/moo_icon16.png');
		if(isset($this->api_key)){
			add_submenu_page($this->plugin_name.'-general', 'Forms', 'Add Form', 'manage_options', $this->plugin_name.'-add-form', array($this, 'display_plugin_add_page'));
			add_submenu_page(null , 'Forms', 'Edit Form', 'manage_options', $this->plugin_name.'-edit-form', array($this, 'display_plugin_add_page'));
			if($forms = get_option('forms') != false){
				add_submenu_page($this->plugin_name.'-general', 'Forms', 'All Forms', 'manage_options', $this->plugin_name.'-all-forms', array($this, 'display_plugin_all_pages'));
			}
		}
	}

	/* By using less css it align the id #preview for each theme style */

	public function add_theme_url(){
		$write_less = "
		#preview {
			@import (less) url('".get_stylesheet_uri()."');
		}";
		file_put_contents(dirname(__FILE__).'/css/ms4wp-preview.less', $write_less.PHP_EOL);
	}

	public function generate_settings_sections($sections) {
		foreach($sections as $id){
			add_settings_section(
				$id,
				'',
				null,
				$this->plugin_name.'-'.$id
				);
		}
	}

	public function generate_settings_fields($fields) {
		foreach($fields['fields'] as $field){
			add_settings_field(
				$field['id'],
				ucwords(str_replace("-", " ", $field['id'])),
				array($this, $field['cb_function']), 
				$this->plugin_name.'-'.$fields['section'],
				$fields['section']
				);
		}
	}

	public function options_update() {
			$sections = array(
				'general-settings-section',
				'add-form-section',
				'custom-fields-section',
				'popup-section'
				);

			$general_settings_fields = array(
				'section' => 'general-settings-section',
				'fields' => array(
						array(
							'id' => 'status',
							'cb_function' => 'create_connected_field'
							),
						array(
							'id' => 'api-key',
							'cb_function' => 'create_api_key_field'
							)
					)
				);

			$form_settings_fields = array(
				'section' => 'add-form-section',
				'fields' => array(
						array(
							'id' => 'mailing-lists*',
							'cb_function' => 'create_dropdown_list_menu'
							),
						array(
							'id' => 'form-name',
							'cb_function' => 'create_form_name_field'
							),
						array(
							'id' => 'form-title',
							'cb_function' => 'create_form_title_field'
							),
						array(
							'id' => 'form-type*',
							'cb_function' => 'create_form_type_field'
							),
						array(
							'id' => 'after-subscription*',
							'cb_function' => 'create_after_subscription_field'
							)
					)
				);

			$popup_settings_fields = array(
				'section' => 'popup-section',
				'fields' => array(
						array(
							'id' => 'loading-delay',
							'cb_function' => 'create_popup_delay_field'
							),
						array(
							'id' => 'exit-intent',
							'cb_function' => 'create_exit_intent_field'
							),
						array(
							'id' => 'popup-frequency',
							'cb_function' => 'create_popup_frequency_field'
							)
					)
				);

			add_settings_field(
				'name',
				'Name',
				array($this, 'create_name_field'), 
				$this->plugin_name.'-custom-fields-section',
				'custom-fields-section'
				);

			add_settings_field(
				'custom-fields',
				'Custom Fields',
				array($this, 'create_custom_fields'), 
				$this->plugin_name.'-custom-fields-section',
				'custom-fields-section'
				);
			

			$this->generate_settings_sections($sections);
			$this->generate_settings_fields($general_settings_fields);
			$this->generate_settings_fields($form_settings_fields);
			$this->generate_settings_fields($popup_settings_fields);
			register_setting('general-settings-group', $this->plugin_name.'-general-settings-section', array($this, 'validate_general_setting_menu')); 
			register_setting('form-group', $this->plugin_name.'-add-form-section', array()); 
			register_setting('popup-settings-group', $this->plugin_name.'-popup-section', array());
			register_setting('custom-fields-group', $this->plugin_name.'-custom-fields-section', array());
		}

	

	public function is_valid_guid($guid) 
	{
		return !empty($guid) && preg_match('/^[{(]?[0-9A-F]{8}[-]?([0-9A-F]{4}[-]?){3}[0-9A-F]{12}[)}]?$/i', $guid);
	}

	public function find_key_value($array, $key, $val) {
	    foreach ($array as $item)
	        if (isset($item[$key]) && $item[$key] == $val)
	            return true;
	    return false;
	}


	public function include_partial($form_id){
		include $this->wcpt_get_template('form-template.php', [], 'partials/fields/');
	}

	//create shortcode for every-entry
	public function register_shortcodes($atts) {

		$a = shortcode_atts(array(
			'id' => intval(0)
			),$atts);
		$this->include_partial($a['id']);	
		ob_start();
		
		$partial = ob_get_contents();
		ob_end_clean();
		return $partial;
	}
	
	/**
	 * Locate template.
	 *
	 * Locate the called template.
	 * Search Order:
	 * 1. /themes/theme/$template_name
	 * 2. /plugins/moosend/templates/$template_name.
	 *
	 * @since 1.0.0
	 *
	 * @param 	string 	$template_name			Template to load.
	 * @param 	string 	$string $template_path	Path to templates.
	 * @param 	string	$default_path			Default path to template files.
	 * @return 	string 							Path to the template file.
	 */
	private function wcpt_locate_template( $template_name, $template_path = '', $default_path = '' ) {
		$template_route = $template_path . $template_name;

		// Search template file in theme folder.
		$template = locate_template([
			$template_route
		]);
		

		// Set default plugin templates path.
		if ( !$template ) {
			$template = $template_route;
		}


		return apply_filters( 'wcpt_locate_template', $template, $template_name, $template_path, $default_path );

	}
	
	
	/**
	 * Get template.
	 *
	 * Search for the template and include the file.
	 *
	 * @since 1.0.0
	 *
	 * @see wcpt_locate_template()
	 *
	 * @param string 	$template_name			Template to load.
	 * @param array 	$args					Args passed for the template file.
	 * @param string 	$string $template_path	Path to templates.
	 * @param string	$default_path			Default path to template files.
	 */
	private function wcpt_get_template( $template_name, $args = [], $tempate_path = '', $default_path = '' ) {

		if ( is_array( $args ) && isset( $args ) ) {
			extract( $args );
		}

		$template_file = $this->wcpt_locate_template( $template_name, $tempate_path, $default_path );

		return $template_file;

	}

	public function is_valid_api($key){
		$endpoint = $GLOBALS['endpoint'];

		$response = wp_remote_get($endpoint.'user/api-key/'.$key, array('headers' => array('Accept' => 'application/json', 'X-ApiKey' => $key)));

		$response_code = wp_remote_retrieve_response_code($response);
		
		if(is_wp_error( $response )) {
			return false; // Bail early
		}

		return $response_code == 200 ? true : false;
	}

	public function ms4wp_get_user_status($key){
		$endpoint = $GLOBALS['endpoint'];

		$response = wp_remote_get($endpoint.'user/api-key/'.$key, array('headers' => array('Accept' => 'application/json', 'X-ApiKey' => $key)));

		$response_code = wp_remote_retrieve_response_code($response);
		
		if(is_wp_error($response)) {
			return false; // Bail early
		}

		$body = wp_remote_retrieve_body($response);
		$data = json_decode($body);

		return $response_code == 200 ? $data->Status : 0;
	}

	public function get_mailing_lists($key)
	{	
		$endpoint = $this->endpoint.'members/lists?pageSize=100';

		$response = wp_remote_get( $endpoint, array('headers' => array('X-ApiKey' => $key)));

		$response_code = wp_remote_retrieve_response_code($response);
		
		if(is_wp_error( $response )) {
			return false; // Bail early
		}

		$body = wp_remote_retrieve_body($response);
		$data = json_decode($body);

		return $response_code == 200 ? $data : null;
	}

	public function get_mailing_list($list){
		$endpoint = $this->endpoint.'members/lists/'.$list;

		$response = wp_remote_get($endpoint, array('headers' => array('X-ApiKey' => $this->api_key)));

		$response_code = wp_remote_retrieve_response_code($response);
		if(is_wp_error( $response )) {
			return false; // Bail early
		}

		$body = wp_remote_retrieve_body($response);
		$data = json_decode($body);

		return $response_code == 200 ? $data : false;
	}

	public function ms4wp_get_query_params() {

			if(isset($_REQUEST['form']) && (isset($_REQUEST['action']) ? $_REQUEST['action'] : null) === 'edit'){
			    $form_id = $_REQUEST['form'];
			    $options = get_option('forms');
			    $form = $options[$form_id];
			}

			if((isset($_REQUEST['action']) ? $_REQUEST['action'] : null) === 'edit' && (isset($_REQUEST['list']) ? $_REQUEST['list'] : null) === null && isset($_REQUEST['form'])){
			    $selected_list = $form->selected_list;
			}else{
			    $selected_list = isset($_REQUEST['list']) ? $_REQUEST['list'] : '';
			}

		return array(
			'selected_list' => isset($selected_list) ? $selected_list : null,
			'form_id' => isset($form_id) ? $form_id : null
			);
	}

	public function add_action_links( $links ) {

		$settings_link = array(
			'<a href="' . admin_url( 'admin.php?page=' . $this->plugin_name.'-general' ) . '">' . __('Settings', $this->plugin_name) . '</a>',
			);
		return array_merge(  $settings_link, $links );

	}

	public function delete_forms_callback() {
		delete_option('forms');
	}

	public function form_params_json_callback() {
	    	global $wpdb; // this is how you get access to the database

	    	$form_params = $_POST['params'];
	    	$forms = get_option('forms');
	    	if(empty($forms)){
	    		$form_params = array('ID' => 1) + $form_params;				
				$new_form = new Moosend_For_Wp_Subscription_Form($form_params['ID']);
	    		$new_form->name = $form_params['name'];
	    		$new_form->title = $form_params['title'];
	    		$new_form->theme = $form_params['theme'];
	    		$new_form->form_type = $form_params['formType'];
	    		$new_form->after_subscription = $form_params['afterSubscription'];
	    		$new_form->new_tab = $form_params['newTab'];
	    		$new_form->selected_list = $form_params['selectedList'];
	    		$new_form->member_name = $form_params['memberName'];
	    		$new_form->custom_fields = $form_params['customFields'];
	    		$new_form->popup_settings = $form_params['popupSettings'];
	    		$new_form->style_settings = $form_params['styleSettings'];
	    		$forms[1] = $new_form;
	    		update_option('forms', $forms);
	    	}else{
	    		end($forms);
				$form_params = array('ID' => key($forms) + 1) + $form_params;
				$new_form = new Moosend_For_Wp_Subscription_Form($form_params['ID']);
	    		$new_form->name = $form_params['name'];
	    		$new_form->title = $form_params['title'];
	    		$new_form->theme = $form_params['theme'];
	    		$new_form->form_type = $form_params['formType'];
	    		$new_form->after_subscription = $form_params['afterSubscription'];
	    		$new_form->new_tab = $form_params['newTab'];
	    		$new_form->selected_list = $form_params['selectedList'];
	    		$new_form->member_name = $form_params['memberName'];
	    		$new_form->custom_fields = $form_params['customFields'];
	    		$new_form->popup_settings = $form_params['popupSettings'];
	    		$new_form->style_settings = $form_params['styleSettings'];
	    		array_push($forms, $new_form);
	    		update_option('forms', $forms);
			}
					
	    	$page = menu_page_url('moosend-for-wp-edit-form&action=edit&form='.$form_params['ID'], true);
            wp_redirect($page);        
            exit; 
	    	wp_die();

	}

	public function edit_form_params_json_callback(){
		global $wpdb; // this is how you get access to the database

			$forms = get_option('forms');
	    	$form_params = $_POST['params'];
	    	$form_id = $_POST['formID'];

	    	if(array_key_exists($form_id, $forms)){
	    		$forms[$form_id]->name = $form_params['name'];
	    		$forms[$form_id]->title = $form_params['title'];
	    		$forms[$form_id]->theme = $form_params['theme'];
	    		$forms[$form_id]->form_type = $form_params['formType'];
	    		$forms[$form_id]->after_subscription = $form_params['afterSubscription'];
	    		$forms[$form_id]->new_tab = $form_params['newTab'];
	    		$forms[$form_id]->selected_list = $form_params['selectedList'];
	    		$forms[$form_id]->member_name = $form_params['memberName'];
	    		$forms[$form_id]->custom_fields = $form_params['customFields'];
	    		$forms[$form_id]->popup_settings = $form_params['popupSettings'];
	    		$forms[$form_id]->style_settings = $form_params['styleSettings'];
	    		update_option('forms', $forms);
	    	}

	    	echo json_encode($form_params);
	    	wp_die();
	}

	public function get_list_json_callback(){
	    global $wpdb; // this is how you get access to the database

	    $list = $_POST['params'];

	    wp_safe_redirect(add_query_arg('list', $list, admin_url($this->plugin_name.'-add-form')));

	    echo json_encode($list);

		wp_die();
	}

		/*
		* Display Function Form Admin Menu
		*/
		public function display_plugin_add_page(){
			include_once('partials/moosend-for-wp-admin-add.php');
		}

		public function display_plugin_setup_page() {
			include_once('partials/moosend-for-wp-admin-display.php');
		}

		public function display_plugin_all_pages() {
			include_once ('partials/moosend-for-wp-admin-all.php');
		}


		/*
		* Moosend For WP - General Settings
		*/

		public function create_api_key_field() {
			include_once('partials/fields/general_config/api-key-field.php');
		}
		public function create_connected_field() {
			include_once('partials/fields/general_config/connected.php');
		}

		/*
		* Add Form - Form Configuration
		*/

		public function create_dropdown_list_menu() {
			include_once('partials/fields/form_config/mailing-lists-dropdown.php');
		}
		public function create_form_type_field() {
			include_once ('partials/fields/form_config/form-type-field.php');
		}
		public function create_form_name_field() {
			include_once ('partials/fields/form_config/form-name-field.php');
		}
		public function create_form_title_field() {
			include_once ('partials/fields/form_config/form-title-field.php');
		}
		public function create_after_subscription_field() {
			include_once ('partials/fields/form_config/after-subscription-field.php');
		}

		/*
		* Add Form - Custom Fields
		*/
		
		public function create_name_field() {
			include_once ('partials/fields/custom_fields/name-field.php');
		}		
		public function create_custom_fields() {
			include_once ('partials/fields/custom_fields/custom-fields-menu.php');
		}	

		/*
		* Add Form - Popup Configuration Fields
		*/
		public function create_popup_delay_field() {
			include_once ('partials/fields/popup_config/popup-delay-field.php');
		}
		public function create_popup_frequency_field() {
			include_once ('partials/fields/popup_config/popup-frequency-field.php');
		}
		public function create_exit_intent_field() {
			include_once ('partials/fields/popup_config/exit-intent-field.php');
		}
		



	//Validations - TO DELETE IF NOT USED
		function validate_general_setting_menu($input) {
			$options = get_option('moosend-for-wp-general-settings-section');
			$options['api_key'] = sanitize_text_field($input['api_key']);

			if (!$this->is_valid_guid($options['api_key']) || !$this->is_valid_api($options['api_key'])){
				$type = 'error';
        		$message = __( 'This is not a valid API Key.', 'my-text-domain' );
				$options['api_key'] = null;
			}else{
				$type = 'updated';
            	$message = __( 'You have successfully updated your API Key.', 'my-text-domain' );
			}

			add_settings_error(
		        'api_key_alerts',
		        esc_attr( 'settings_updated' ),
		        $message,
		        $type
		    );

			return $options;
		}
}

