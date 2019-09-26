<?php


if ( function_exists( "register_field_group" ) ) {
	/* Campo */
	register_field_group(array (
		'id' => 'acf_dt3_rating',
		'title' => '<h1>Dados da Avaliação</h1>',
		'fields' => array (
			/* array (
				'key' => 'field_4b73da531g8ec',
				'label' => 'Título',
				'name' => 'dt3_rating_title',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			), */
			array (
				'key' => 'field_4b73da531g8f5',
				'label' => 'ID do Produto',
				'name' => 'dt3_rating_post_id',
				'type' => 'text',
				'default_value' => '0',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_4b73da531g8f6',
				'label' => 'Nome do Produto',
				'name' => 'dt3_rating_post_title',
				'type' => 'text',
			),
			array (
				'key' => 'field_4b73da531g8ed',
				'label' => 'Avaliação',
				'name' => 'dt3_rating_stars',
				'type' => 'number',
				'prepend' => '',
				'append' => '',
				'min' => '0',
				'max' => '5',
				'step' => '1',
			),
			array (
				'key' => 'field_4b73da531g8ee',
				'label' => 'Nome',
				'name' => 'dt3_rating_name',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '100',
			),
			array (
				'key' => 'field_4b73da531g8ef',
				'label' => 'Email',
				'name' => 'dt3_rating_email',
				'type' => 'email',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '100',
			),
			array (
				'key' => 'field_4b73da531g8f0',
				'label' => 'Pontos positivos',
				'name' => 'dt3_rating_positive',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '300',
			),
			array (
				'key' => 'field_4b73da531g8f1',
				'label' => 'Poderia melhorar',
				'name' => 'dt3_rating_negative',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '300',
			),
			array (
				'key' => 'field_4b73da531g8f2',
				'label' => 'Conforto',
				'name' => 'dt3_rating_confort',
				'type' => 'number',
				'prepend' => '',
				'append' => '',
				'min' => '0',
				'max' => '5',
				'step' => '1',
			),
			array (
				'key' => 'field_4b73da531g9f2',
				'label' => 'Qualidade',
				'name' => 'dt3_rating_quality',
				'type' => 'number',
				'prepend' => '',
				'append' => '',
				'min' => '0',
				'max' => '5',
				'step' => '1',
			),
			array (
				'key' => 'field_4b73da531g8f3',
				'label' => 'Características',
				'name' => 'dt3_rating_features',
				'type' => 'number',
				'prepend' => '',
				'append' => '',
				'min' => '0',
				'max' => '5',
				'step' => '1',
			),
			array (
				'key' => 'field_4b73da531g8f4',
				'label' => 'Recomendaria',
				'name' => 'dt3_rating_recomendations',
				'type' => 'radio',
				'choices' => array(
					'yes'	=> 'Sim',
					'no'	=> 'Não',
				),
				'other_choice' => 0,
				'save_other_choice' => 0,
				'layout' => 'horizontal',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'dt3-rating',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
}
