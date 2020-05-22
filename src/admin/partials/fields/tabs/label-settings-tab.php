<?php 

	$id = 'label-settings';
	$settings = array(
		array(
			'name' => 'font-size',
			'type' => 'number'
			),
		array(
			'name' => 'font-style',
			'type' => 'dropdown',
			'options' => array(
				'normal',
				'italic',
				'oblique'
				)
			),
		array(
			'name' => 'font-weight',
			'type' => 'dropdown',
			'options' => array(
				'normal',
				'bold'
				)
			),
		array(
			'name' => 'font-variant',
			'type' => 'dropdown',
			'options' => array(
				'normal',
				'small-caps'
				)
			),
		array(
			'name' => 'font-color',
			'type' => 'text'
			)
		);
?>