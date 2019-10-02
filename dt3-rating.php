<?php
/*
 * Plugin Name: DT3 Rating
 * Plugin URI: https://www.dt3.com.br/
 * Description: Plugin de Avaliação da DT3
 * Version: 0.1.5
 * Author: DT3
 * Author URI: https://www.dt3.com.br/
 * Text Domain: dt3-rating
 */

require __DIR__ . '/vendor/autoload.php';
include_once 'define.php';

use DT3\Security;
use DT3\Panel;
use DT3\Plugin;

Security::denyDirectAccess();
Panel::init();
Plugin::init();

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
