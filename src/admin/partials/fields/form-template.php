<?php 

if ( ! defined( 'WPINC' ) ) {
	die;
}

$options = get_option('forms');

if(isset($options[$form_id]))
{
	$form =  $options[$form_id];

	$inputTypeMap = array(
		0 => 'text',
		1 => 'number',
		2 => 'date',
		3 => 'dropdown',
		5 => 'checkbox'
	);

	$field_settings_arr = $form->get_field_settings();
	$title_settings_arr = $form->get_title_settings();
	$label_settings_arr = $form->get_label_settings();
	$button_settings_arr = $form->get_button_settings();

	$title_style = 'text-align: '.strtolower($title_settings_arr[0]['title-text-align']).';
	font-size: '.$title_settings_arr[1]['title-font-size'].'px;
	font-style: '.strtolower($title_settings_arr[2]['title-font-style']).';
	font-weight: '.strtolower($title_settings_arr[3]['title-font-weight']).';
	color: '.$title_settings_arr[4]['title-font-color'].';';

	$field_style = 'background-color: '.$field_settings_arr[0]['field-color'].';
	border-radius: '.$field_settings_arr[1]['field-corner-radius'].'px;
	border-width: '.$field_settings_arr[2]['field-border-size'].'px;
	border-color: '.$field_settings_arr[4]['field-border-color'].';';

	$button_label = $button_settings_arr[5]['button-label'];

	$button_style = 'background-color: '.$button_settings_arr[0]['button-color'].';
	color: '.$button_settings_arr[1]['button-label-font-color'].';
	border-radius: '.$button_settings_arr[2]['button-corner-radius'].'px;
	border-width: '.$button_settings_arr[3]['button-border-thickness'].'px;
	border-color: '.$button_settings_arr[4]['button-border-color'].';';

	$label_style = 'font-size: '.$label_settings_arr[0]['font-size'].'px;
	font-style: '.strtolower($label_settings_arr[1]['font-style']).';
	font-weight: '.strtolower($label_settings_arr[2]['font-weight']).';
	font-variant: '.strtolower($label_settings_arr[3]['font-variant']).';
	color: '.$label_settings_arr[4]['font-color'].';';

	$align = $form->theme;
	$form_type = $form->form_type;
	$member_name_exists = $form->member_name === "true";

	$popup = ($form_type === "popup-form") ? 'style="display:none"' : "";

	if(($form->selected_list != null) && !empty($this->api_key))
	{
		$mailingList = $this->get_mailing_list($form->selected_list);
		?>
		<div id="ms4wp-<?php echo $form_id ?>" <?php echo $popup; ?> class="<?php echo $align." ".$form_type ?>">
			<form  method="post" id="ms-sub-form">
				<h3 style='<?php echo $title_style ?>'><?php echo $form->title ?></h3>
				<div class="form-block">
					<label for="email" style='<?php echo $label_style ?>'>
						<span>Email*: </span>
					</label>
					<input type="email" name="MemberEmail" id="email" style='<?php echo $field_style ?>' required/>
				</div>
				<?php if($member_name_exists){ ?>
				<div class="form-block">
					<label for="name" style='<?php echo $label_style ?>'>
						<span>Name: </span>
					</label>
					<input type="text" name="MemberEmail" id="name" style='<?php echo $field_style ?>' />
				</div>
				<?php } ?>
				<div id="custom-fields">
				<?php if(($form->custom_fields) != null){
					$customFields = (array) $mailingList->CustomFieldsDefinition;
					foreach ($form->custom_fields as $selectedField): 
						foreach ($customFields as $remoteField): 

							if($selectedField['value'] === $remoteField->ID){ 
								?>

								<div class="form-block">                          
									<label for="<?php _e($remoteField->Name) ?>" style='<?php echo $label_style ?>'>
										<span><?php _e($remoteField->Name); echo $remoteField->IsRequired ? '*' : ''; ?>: </span>
									</label> 
									<?php
									if($remoteField->Type === 5){
										?>
										<label class="checkbox-label" style="width: 16px;height: 16px;">
										<input class="checkbox-style" style='<?php echo $field_style ?>' type="<?php _e($inputTypeMap[$remoteField->Type]) ?>" 
										name="<?php _e($remoteField->Name) ?>"
										id="<?php _e($remoteField->Name) ?>"
										value="true" onchange="document.getElementById('h_<?php _e($remoteField->Name) ?>').name=(this.checked ? '' : this.name)"/>
										</label>
										<input id="h_<?php _e($remoteField->Name) ?>" name="<?php _e($remoteField->Name) ?>"  type="hidden" value="false"/>
										<?php
									}else if ($remoteField->Type === 3) {
										?> 
										<?php 
										$dropdown_json = json_encode(simplexml_load_string($remoteField->Context));
										$dropdown = json_decode($dropdown_json, TRUE);
										?>
										<select name="<?php _e($remoteField->Name) ?>" id="<?php _e($remoteField->Name) ?>" style='<?php echo $field_style ?>'  <?php echo $remoteField->IsRequired ? 'required' : ''; ?>>
											<?php 
											foreach($dropdown['item'] as $dropdown_option){
												_e('<option>'.$dropdown_option['value'].'</option>', $this->plugin_name);
											}
											?>
										</select>
										<?php
									}else{
										?>
										<input type="<?php _e($inputTypeMap[$remoteField->Type]) ?>" name="<?php _e($remoteField->Name) ?>" id="<?php _e($remoteField->Name) ?>" style='<?php echo $field_style ?>'  <?php echo $remoteField->IsRequired ? 'required' : ''; ?>/>
										<?php	
									}
									?>
								</div>
								<?php } endforeach ?>
							<?php endforeach ?>
							<?php } ?>
							
							</div>
							<div class="ms-form-submit-container">
							<button type="submit" value="Subscribe" id="sub-button" style='margin-top: 10px;<?php echo $button_style ?>' ><?php echo $button_label ?></button>
							</div>  
							</form>
							</div>

				<?php } ?>
							<?php 
						}else{
							echo '<p class="help">The shortcode you provided is not valid.<br>Please copy and paste the shortcode from the "Moosend For WP" plugin form list.</p>';
							exit;
						}
						?>