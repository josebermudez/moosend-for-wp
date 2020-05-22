<?php

	$options = get_option('forms');
	$query_params = $this->ms4wp_get_query_params();
	$form = isset($query_params['form_id']) ? $options[$query_params['form_id']] : null;

	if(!empty($this->api_key)) 
	{
		$mailing_lists = $this->get_mailing_lists($this->api_key);
	}
?>

<select name="lists-dropdown" id="lists-dropdown">
	<option value="">Select Mailing List</option>
	<?php 
		foreach($mailing_lists->Items as $mailingList)
		{	
	?>		
			<option name="<?php echo $mailingList->Name ?>" <?php echo $query_params['selected_list'] === $mailingList->ID ? 'selected' : ''; ?> value='<?php echo $mailingList->ID; ?>'><?php _e($mailingList->Name) ?></option>
	<?php 
		}
	?>
</select>
<span style="margin-left:10px; color:red; display: inline-block;" id="lists-error-span"></span>
<p class="help">
<?php _e( 'Select the mailing list you want users to subscribe to', $this->plugin_name ); ?>
</p>




