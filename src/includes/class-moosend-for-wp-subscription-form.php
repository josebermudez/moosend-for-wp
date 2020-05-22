<?php

if ( ! defined( 'WPINC' ) ) {
	die;
}

class Moosend_For_Wp_Subscription_Form {
	public $id;
	public $name;
	public $title;
	public $theme;
	public $form_type;
	public $shortcode;
	public $after_subscription;
	public $new_tab;
	public $selected_list;
	public $member_name;
	public $custom_fields;
	public $popup_settings;
	public $style_settings;

	public function __construct($id) {
		$this->id = $id;
		$this->shortcode = "[ms-form id=".$id."]";
	}

	private function merge_values($array){
		$merged_arr = array();

		foreach ($array as $key => $inner_array) {
			$temp = array_values($inner_array);
			array_push($merged_arr, array($temp[0] => $temp[1]));
		}
		return $merged_arr;
	}

	public function get_field_settings(){
		return $this->merge_values($this->style_settings['fieldSettings']);
	}

	public function get_title_settings(){
		return $this->merge_values($this->style_settings['titleSettings']);
	}

	public function get_label_settings(){
		return $this->merge_values($this->style_settings['labelSettings']);
	}

	public function get_button_settings(){
		return $this->merge_values($this->style_settings['buttonSettings']);
	}
}