<?php

use DT3Rating\Rating;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


// AJAX

function dt3_save_rating () {

	/*$check = wp_verify_nonce($_POST['dt3_numb'], 'woocommerce-cart');

	if(false == $check) {
		echo 'Operação inválida';
		die();

	} else {*/

		if (isset($_POST['rating_stars'])) {

			$rating_title = esc_html($_POST['rating_title']);
			$rating_stars = esc_html($_POST['rating_stars']);
			$rating_name = esc_html($_POST['rating_name']);
			$rating_email = esc_html($_POST['rating_email']);
			$rating_positive = esc_html($_POST['rating_positive']);
			$rating_negative = esc_html($_POST['rating_negative']);
			$rating_confort = esc_html($_POST['rating_confort']);
			$rating_quality = esc_html($_POST['rating_quality']);
			$rating_features = esc_html($_POST['rating_features']);
			$rating_recomendations = esc_html($_POST['rating_recomendations']);
			$product_post_id = esc_html($_POST['product_post_id']);

			// Gera o título da avaliação:
			// $product_title = get_the_title( $product_post_id );
			$product_post_title = get_the_title( $product_post_id );
			// $rating_title =  $product_title .' - '. $rating_title;

			// Cria um novo post do tipo dt3-rating
			// Atribui o titulo
			$post = array(
	    	    'post_status' 	  => 'pending',
	    	    'post_title' 	  => $rating_title,
	    	    'post_type' 	  => 'dt3-rating',
	    	    'comment_status'  => 'closed',
	    	);
			$post_id = wp_insert_post( $post ); // Insert the post

			update_field( 'dt3_rating_stars', $rating_stars, $post_id );
			update_field( 'dt3_rating_name', $rating_name, $post_id );
			update_field( 'dt3_rating_email', $rating_email, $post_id );
			update_field( 'dt3_rating_positive', $rating_positive, $post_id );
			update_field( 'dt3_rating_negative', $rating_negative, $post_id );
			update_field( 'dt3_rating_confort', $rating_confort, $post_id );
			update_field( 'dt3_rating_quality', $rating_quality, $post_id );
			update_field( 'dt3_rating_features', $rating_features, $post_id );
			update_field( 'dt3_rating_recomendations', $rating_recomendations, $post_id );
			update_field( 'dt3_rating_post_id', $product_post_id, $post_id );
			update_field( 'dt3_rating_post_title', $product_post_title, $post_id );

			$status = 1;

		} else {

			$status = 0;

		}

		echo json_encode( $status );

		die();

	// }

}

/*
 * Hooks que passam a função para o wp_ajax
 */
add_action('wp_ajax_dt3_save_rating', 'dt3_save_rating'); // Logged-in users
add_action('wp_ajax_nopriv_dt3_save_rating', 'dt3_save_rating'); // Guest users

// Returns the next 4 posts
function dt3_load_rating () {

	/*$check = wp_verify_nonce($_POST['dt3_numb'], 'woocommerce-cart');

	if(false == $check) {
		echo 'Operação inválida';
		die();

	} else {*/

		if (isset($_POST['rating_loaded'])) {

			$rating_loaded 		= esc_html($_POST['rating_loaded']);
			$product_post_id	= esc_html($_POST['product_post_id']);

			$rating_to_load = $rating_loaded + 1;

			// Executes the query with offset = data-load + 1
			$ajax_loop = new WP_Query( array(
				'post_type' 		=> 'dt3-rating',
				'posts_per_page' 	=> 4,
				'offset' 			=>  $rating_to_load,
				'meta_key'          => 'dt3_rating_post_id',
    			'meta_value'        => $product_post_id,
			));

			// Number of ratings loaded
            $ratings_loaded = $rating_to_load;

            // Ratings on a string
            $rating_string = '';

			// Estrutura o conteúdo em uma string
			 while ( $ajax_loop->have_posts() ) :
			 	$ajax_loop->the_post();

                $rating_post_id       = get_the_ID();
                $rating_stars 	      = Rating::get_stars($rating_post_id);
                $rating_title 	      = get_the_title();
                $rating_name 	        = Rating::get_field('dt3_rating_name', $rating_post_id);
                $rating_time 	        = get_the_modified_time('d/m/Y');
                $rating_positive      = Rating::get_field('dt3_rating_positive', $rating_post_id);
                $rating_negative      = Rating::get_field('dt3_rating_negative', $rating_post_id);
                $rating_recomendation = Rating::get_recommendation($rating_post_id);

                $rating_string .= '<div class="comment-item" data-load="'. $ratings_loaded .'">';
                $rating_string .= '<div class="comment-stars">'. $rating_stars .'</div>';
                $rating_string .= '<div class="comment-title">';
                $rating_string .= '<h3>' . $rating_title . '</h3>';
                $rating_string .= '</div>';
                $rating_string .= '<div class="comment-user-date">';
                $rating_string .= '<p>Por'. $rating_name .' em '. $rating_time . '</p>';
                $rating_string .= '</div>';
                $rating_string .= '<div class="comment-positive-point">';
                $rating_string .= '<p><span>Pontos positivos:</span>'. $rating_positive .'</p>';
                $rating_string .= '</div>';
                $rating_string .= '<div class="comment-negative-point">';
                $rating_string .= '<p><span>Poderia melhorar:</span>'. $rating_negative .'</p>';
                $rating_string .= '</div>';
                $rating_string .= '<div class="comment-recommended">'. $rating_recomendation .'</div>';
            	$rating_string .= '</div>';

                // Incrementa o numero de ratings carregados
                $ratings_loaded++;

                endwhile;

		} else {
			$rating_loaded = 'Não enviou nada';
		}

		// Retorna os itens carregados
		echo json_encode( $rating_string );

		die();

	// }
}

/*
 * Hooks que passam a função para o wp_ajax
 */
add_action('wp_ajax_dt3_load_rating', 'dt3_load_rating'); // Logged-in users
add_action('wp_ajax_nopriv_dt3_load_rating', 'dt3_load_rating'); // Guest users

