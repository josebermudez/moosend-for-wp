<?php 
	$existing_delay = 0;

	$options = get_option('forms');
	$query_params = $this->ms4wp_get_query_params();
	$popup_settings = isset($query_params['form_id']) ? $options[$query_params['form_id']]->popup_settings : null;

	if($popup_settings !== null){
        $existing_delay = $popup_settings['popupDelay'];
    }
?>

<input type="number" name="popup-delay" id="popup-delay" min="0" value="<?php echo intval($existing_delay); ?>" />
<p class="help">
	<?php _e( 'This is how long the page should wait before showing the form. (Defaults to 0 seconds).', $this->plugin_name ); ?>
</p>