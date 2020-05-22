<?php 

$options = get_option('forms');
$query_params = $this->ms4wp_get_query_params();
$form = isset($query_params['form_id']) ? $options[$query_params['form_id']] : null;

$val_exists = null;


$url = "https://user.moosend.com/#/mailing-lists/".$query_params['selected_list']."/custom-fields";

if(!empty($query_params['selected_list']) && !empty($this->api_key))
{ 
	$mailingList = $this->get_mailing_list($query_params['selected_list']);

	$customFields = (array) $mailingList->CustomFieldsDefinition;
	?>
	<fieldset  id = 'custom-fields'>
		<?php 
		if(!empty($mailingList->CustomFieldsDefinition))
		{
			foreach($customFields as $custom_field)
			{
				if($custom_field->IsRequired){
					$val_exists = true;
					echo '<input type="hidden" id="'.$custom_field->ID.'" name="'.$custom_field->Name.'" value="'.$custom_field->ID.'"/>';
				}else{
					if(isset($query_params['form_id']) && ($form->custom_fields != null))
					{	
						$val_exists = $this->find_key_value($form->custom_fields, 'value', $custom_field->ID);
					}else{
						$val_exists = false;
					}
				}
				?>
				<div>
					<label for"<?php echo $custom_field->ID; ?>">
						<input type="checkbox" id="<?php echo $custom_field->ID; ?>" name="<?php echo $custom_field->Name ?>" value="<?php echo $custom_field->ID; ?>" <?php echo($val_exists ? ' checked' : ''); echo $custom_field->IsRequired ? ' disabled' : ''; ?>/>
						<span><?php _e($custom_field->Name) ?></span>
					</label>
				</div>
				<?php
			}
			?>
		</fieldset>
		<p class="help">
			<?php _e( 'Select the custom fields you want in your subscription form', $this->plugin_name ); ?>
		</p>
		<?php }else{ ?>
		<p class="help">
			<?php _e( 'You have not created any custom fields for this mailing list.', $this->plugin_name ); ?>
			<a target="_blank" href="<?php echo esc_url( $url ); ?>">
				<?php _e( 'You can create custom fields here.', $this->plugin_name ); ?>				
			</a>
			<br>
			<?php _e( 'Or just click next.', $this->plugin_name ); ?>
		</p>
		<?php 
	}
} 	
?>