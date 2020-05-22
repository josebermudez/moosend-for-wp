<?php
	$existing_form_type = null;
	$options = get_option('forms');
	$query_params = $this->ms4wp_get_query_params();
	$form = isset($query_params['form_id']) ? $options[$query_params['form_id']] : null;

	if(isset($query_params['form_id'])){
		$existing_form_type = $form->form_type ?: null;
    }
?>


<fieldset id="form-type-field">
	<legend class="screen-reader-text"><span>input type="radio"</span></legend>
	<label title='Regular Form'>
		<input type="radio" name="form-type" value="regular-form" <?php echo ($existing_form_type === "regular-form") ? "checked" : "" ?>/>
		<span><?php esc_attr_e( 'Regular Form', $this->plugin_name); ?></span>
		<span style="margin-left:10px; color:red; display: inline-block;" id="type-error-span"></span>
	</label>
	<p class="help">
	    <?php _e( 'Creates a regular form that you can place anywhere you want in your wordpress website.', $this->plugin_name ); ?>
	</p>
	<br>
	<label title='Popup Form'>
		<input type="radio" name="form-type" value="popup-form" <?php echo ($existing_form_type === "popup-form") ? "checked" : "" ?>/>
		<span><?php esc_attr_e( 'Popup Form', $this->plugin_name); ?></span>
	</label>
	<p class="help">
	    <?php _e( 'Creates a popup form that can show up to the user at your specified time', $this->plugin_name ); ?>
	</p>
</fieldset>

<div id="popup-settings" hidden>
<h3><?php esc_attr_e( 'Popup Configuration', $this->plugin_name ); ?></h3>
<?php 
    settings_fields('popup-group');
    do_settings_sections($this->plugin_name.'-popup-section');
?>
</div>
