<?php 

	$id = 'title-settings';
	$settings = array(
		array(
			'name' => 'title-text-align',
			'type' => 'dropdown',
			'options' => array(
				'left',
				'right',
				'center'
				)
			),
		array(
			'name' => 'title-font-size',
			'type' => 'number'
			),
		array(
			'name' => 'title-font-style',
			'type' => 'dropdown',
			'options' => array(
				'normal',
				'italic',
				'oblique'
				)
			),
		array(
			'name' => 'title-font-weight',
			'type' => 'dropdown',
			'options' => array(
				'normal',
				'bold'
				)
			),
		array(
			'name' => 'title-font-color',
			'type' => 'text'
			)
		);
?>