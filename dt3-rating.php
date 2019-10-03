<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/*
 * Plugin Name: DT3 Rating
 * Plugin URI: https://www.dt3.com.br/
 * Description: Plugin de Avaliação da DT3
 * Version: 0.2.0
 * Author: DT3
 * Author URI: https://www.dt3.com.br/
 * Text Domain: dt3-rating
 */

// ADMINISTRAÇÃO

include_once 'define.php';

// include_once 'admin.php';

// ADMINISTRAÇÃO

// Add the admin options page

function dt3_rating_admin_add_page() {
	add_options_page('DT3 Rating Page', 'DT3 Rating Menu', 'manage_options', 'plugin', 'dt3_rating_options_page');
}
add_action('admin_menu', 'dt3_rating_admin_add_page');

// Display the admin options page
function dt3_rating_options_page() {

	?>
	<div>
	<h2>DT3 Rating</h2>
	<h3>Dependências</h3>
	<ul>
		<li>
			ACF:
			<?php 

			//  Melhore a forma de verificar se o ACF está instalado.
			if(function_exists("register_field_group")) {
				echo 'Instalado';
			} else {
				echo 'ACF não encontrado no tema';
				echo MY_ACF_PATH;
				echo MY_ACF_URL;
			}

			?>
		</li>
	</ul>

	Configure as opções do plugin abaixo.
	<form action="options.php" method="post">
		<?php settings_fields('dt3_rating_options'); ?>
		<?php do_settings_sections('dt3_rating'); ?>
	<input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
	</form></div>
	
	<?php
}

// Add the admin settings and such
function dt3_rating_admin_init () {

	register_setting( 'dt3_rating_options', 'dt3_rating_options', 'dt3_rating_options_validate' );
	
	// Cria Post Type dt3-rating
	// register_post_type('dt3-rating', $dt3_rating_registry );

	add_settings_section('dt3_rating_main', 'Seção 1', 'dt3_rating_section_text', 'dt3_rating');
	add_settings_field('dt3_rating_text_string', 'DT3 Rating Text Input', 'dt3_rating_setting_string', 'dt3_rating', 'dt3_rating_main');
}
add_action('admin_init', 'dt3_rating_admin_init');

// Add the text
function dt3_rating_section_text() {
	echo '<p>Descrição da seção.</p>';
} 

// Make one field to the form
function dt3_rating_setting_string() {
	$options = get_option('dt3_rating_options');
	// var_dump( $options );
	echo "<input id='dt3_rating_text_string' name='dt3_rating_options[text_string]' size='40' type='text' value='{$options['text_string']} ' />";
} 

// Validate our options
function dt3_rating_options_validate($input) {

	$newinput['text_string'] = trim($input['text_string']);
	
	// if(!preg_match('/^[a-z0-9]$/i', $newinput['text_string'])) {
	if(!preg_match('([A-Z])', $newinput['text_string'])) {
		$newinput['text_string'] = '';
	}
	return $newinput;
}


// Cria o tipo de post Ratings
function dt3_rating_crate_post_type() {

    register_post_type('dt3-rating', array(
        'labels' => array(
	        'name' => _x('Avaliações', 'post type general name'),
	        'singular_name' => _x('Avaliação', 'post type singular name'),
	        // 'add_new' => _x('Adicionar Nova', 'Avaliação '),
	        // 'add_new_item' => __('Adicionar Avaliação '),
	        'edit_item' => __('Editar Avaliação '),
	        // 'new_item' => __('Nova Avaliação '),
	        // 'view_item' => __('Ver Avaliação '),
	        'search_items' => __('Procurar Rating'),
	        'not_found' =>  __('Nada encontrado'),
	        'not_found_in_trash' => __('Nada encontrado na lixeira'),
	        'parent_item_colon' => '',
	    	),
	    'public'             => true,
	    'query_var'          => true,
	    'capability_type'    => 'post',
	    'has_archive'        => true,
	    'hierarchical'       => false,
	    'menu_position'      => 25,
	    // 'supports'           => array( 'title', 'page-attributes' ),
	    'supports'           => array( 'title' ),
		)
    );

}

add_action('init', 'dt3_rating_crate_post_type');


// Define path and URL to the ACF plugin.

// Include the ACF plugin.
include_once( MY_ACF_PATH . 'acf.php' );

// Customize the url setting to fix incorrect asset URLs.
function my_acf_settings_url( $url ) {
    return MY_ACF_URL;
}
add_filter('acf/settings/url', 'my_acf_settings_url');

// (Optional) Hide the ACF admin menu item.
function my_acf_settings_show_admin( $show_admin ) {
 
    return false;

}
add_filter('acf/settings/show_admin', 'my_acf_settings_show_admin');

/*
// Start form scripts
function dt3_rating_init_frontend_form() {

	// Corrigir a altura do header
	acf_form_head();

}
add_action('init', 'dt3_rating_init_frontend_form');
*/	

add_action( 'wp_ajax_dt3_acf_save_data', 'acf_form_head' );
add_action( 'wp_ajax_nopriv_dt3_acf_save_data', 'acf_form_head' );



// Insert rating form in comments
function dt3_rating_form() {
	
	include_once 'template_functions.php';
	include_once 'template.php';

}

add_action( 'wp_get_current_commenter', 'dt3_rating_form');

// AJAX
include_once 'dt3_rating_ajax.php';

// FIELDS
include_once 'fields.php';
