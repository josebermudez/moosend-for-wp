<?php 
	$existing_val = null;

	$options = get_option('forms');
	$query_params = $this->ms4wp_get_query_params();
	$form = isset($query_params['form_id']) ? $options[$query_params['form_id']] : null;

	if(isset($query_params['form_id'])){
		$form = $options[$query_params['form_id']];
		switch ($id) {
			case 'field-settings':
	    		$existing_settings = $form->get_field_settings();
				break;
			case 'label-settings':
	    		$existing_settings = $form->get_label_settings();
	    		break;
			case 'button-settings':
				$existing_settings = $form->get_button_settings();
	    		break;
	    	case 'title-settings':
	    		$existing_settings = $form->get_title_settings();
	    		break;
			default:
				$existing_settings = null;
				break;
		}
	}else{
		$form = null;
	}

?>

	<table class="form-table">
	<tbody id='<?php echo $id; ?>'>
	<?php
	foreach($settings as $field){ 
		if(isset($query_params['form_id']) && !empty($existing_settings))
		{	
			foreach ($existing_settings as $inner_arr) {
				if (array_key_exists($field['name'], $inner_arr)){
					$existing_val = $inner_arr[$field['name']];
				}
			}
		}
		?>	
			<tr>
			<th scope="row"><?php _e(ucwords(str_replace("-", " ", $field['name'])), $this->plugin_name); ?></th>
				<td>
					<div>
						<label for="<?php echo $field['name']; ?>">
	            			<?php
	            			if ($field['type'] === 'dropdown') {
	            			?> 
	            				<select name="<?php echo $field['name']; ?>" id="<?php echo $field['name']; ?>">
	            					<option>Default</option>
	            					<?php
	            						foreach ($field['options'] as $option) {
	            							$equal = strcmp($option, strtolower($existing_val));
	            							?>
	            								<option <?php echo (($equal === 0) ? 'selected' : ''); ?>><?php echo ucwords($option); ?></option>
	            							<?php
	            						}
	            					?>
	            				</select>
	            			<?php
	            			}else if($field['type'] === 'number'){
	            			?>
	            				<input type="<?php echo $field['type'] ?>" min="0" max="100" name="<?php echo $field['name']; ?>" id="<?php echo $field['name']; ?>" 
	            				value="<?php _e($existing_val, $this->plugin_name) ?>"/>
	            			<?php
							}else if($field['type'] === 'text-field') {
							?>
								<input type="text" name="<?php echo $field['name']; ?>" id="<?php echo $field['name']; ?>" 
								value="<?php 
									if(empty($existing_val)){
										_e("Subscribe", $this->plugin_name);
									}else{
										_e($existing_val, $this->plugin_name);
									}
								?>"/>
							<?php
	            			}else{
	            			?>
	            				<input type="<?php echo $field['type'] ?>" name="<?php echo $field['name']; ?>" id="<?php echo $field['name']; ?>" class="color-picker"
	            				value="<?php _e($existing_val, $this->plugin_name) ?>"/>
	            			<?php
	            			}
	            			?>
						</label>
					</div>
				</td>
			</tr>
		<?php
	}
?>
</tbody>
</table>