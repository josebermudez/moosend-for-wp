<?php 
	$existing_frequency = 7;

	$options = get_option('forms');
	$query_params = $this->ms4wp_get_query_params();
	$popup_settings = isset($query_params['form_id']) ? $options[$query_params['form_id']]->popup_settings : null;

	if($popup_settings !== null){
        $existing_frequency = $popup_settings['popupFrequency'];
    }
?>

<input type="number" name="popup-frequency" id="popup-frequency" min="0" value="<?php echo intval($existing_frequency); ?>"/>
<p class="help">
	<?php _e( 'Do NOT show the popup to the same visitor again until this many days have passed. (Defaults to 7 days).', $this->plugin_name ); ?>
</p>