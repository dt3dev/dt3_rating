<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/*

Define as funções a serem executadas para carregamento do template

Exibe os comentários anteriores
Avaliação (stars)
Nome
Título (Pode ser buscado do POST)
Pontos positivos (300 caracteres)
Poderia melhorar (300 caracteres)
Recommendaria
Data / Hora (Pode ser buscado do POST)

Dados calculados:
Médias das avaliações (stars)
Total de avaliações
Porcentagem de cada quantidade de estrela
Total das recommendações
Média do conforto
Média da qualidade
Média das características

*/

include_once 'define.php';

// Receive the name of the field and returns the data in the loop
function dt3_rating_get_field( $field_name ) {

	// Busca o ID detro do loop
	$post_id = get_the_ID();
	
	$field_data = get_post_meta( $post_id, $field_name, true);

	return $field_data;
}

// Receive the name of the field and show the data in the loop
function dt3_rating_the_field( $field_name ) {

	// Busca o ID detro do loop
	$post_id = get_the_ID();
	
	$field_data = get_post_meta( $post_id, $field_name, true);

	echo $field_data;
}

// Returns the number of stars and show in the loop
function dt3_rating_the_stars () {

	$rating_stars = intval( dt3_rating_get_field( 'dt3_rating_stars' ) ); 
    $s = 5;
    $i = 1;
    while ( $i <= 5) {
        
        if ( $i <= $rating_stars ) {
            echo "<img src=". PLUGIN_URL ."dt3-rating/images/red-star.svg' alt='' >";
        } else {
            echo "<img src=". PLUGIN_URL ."dt3-rating/images/white-star.svg' alt='' >";
        }
        $i++;
    }
}

// Returns the number of stars and in the loop
function dt3_rating_get_stars () {

	$rating_stars = intval( dt3_rating_get_field( 'dt3_rating_stars' ) ); 
    $s = 5;
    $i = 1;
    $rating_stars_string = '';
    while ( $i <= 5) {
        
        if ( $i <= $rating_stars ) {
            $rating_stars_string .= '<img src="'. PLUGIN_URL .'dt3-rating/images/red-star.svg" alt="" >';
        } else {
            $rating_stars_string .= '<img src="'. PLUGIN_URL .'dt3-rating/images/white-star.svg" alt="" >';
        }
        $i++;
    }

    return $rating_stars_string;
}

// Bar rate
// Receives the rate and returns the bars of rate
function dt3_rating_the_rate ( $rate ) {

	$rate = intval( $rate );
	$s = 5;
    $i = 1;
    while ( $i <= 5) {
        
        if ( $i == $rate ) {
            echo '<span class="add-color-rate"></span>';
        } else {
            echo '<span></span>';
        }
        $i++;
    }

}

// Generic average
// Receive an array of numbers and returns the average of this numbers
function dt3_rating_average ( $numbers, $product_post_id = '5' ) {

	$total_numbers = 0;

	$total_numbers = count( $numbers );

	$total_numbers += intval( dt3_rating_woo_reviews( $product_post_id ) );
	
	$woo_all_reviews = dt3_rating_woo_all_reviews( $product_post_id );

	if ( 0 != $total_numbers ) {
		
		// Calculate the average
		$average = (array_sum( $numbers ) + $woo_all_reviews) / $total_numbers;
		
		// Calculate the round in one decimal	
		$average = round( $average, 1 );

	} else {

		$average = NULL;

	}

	return $average;
	// return $total_numbers;
}

// Average of avaliations
// Receives the loop. Make an array of avaliations and return the average
function dt3_rating_stars_average( $loop, $product_post_id = '5' ) {
	
	$array_stars = array();
	$p = 0;

	while ( $loop->have_posts() ) : 
		$loop->the_post();

		$array_stars[ $p ] = dt3_rating_get_field( 'dt3_rating_stars' );

		$p++;

	endwhile;

	$stars_average = dt3_rating_average( $array_stars, $product_post_id );

	return $stars_average;

}

// Receives the number of stars and show star images in the average of avaliations
function dt3_rating_the_averarge_stars ( $average ) {

    $s = 5;
    $i = 1;
    while ( $i <= 5) {
        
        if ( $i <= $average ) {
            echo "<img src=". PLUGIN_URL ."dt3-rating/images/red-star.svg' alt='' >";
        } else {
            echo "<img src=". PLUGIN_URL ."dt3-rating/images/white-star.svg' alt='' >";
        }
        $i++;
    }
}

// Average of confort
function dt3_rating_confort_average ( $loop ) {

	$array_stars = array();
	$p = 0;

	while ( $loop->have_posts() ) : 
		$loop->the_post();

		$array_stars[ $p ] = dt3_rating_get_field( 'dt3_rating_confort' );

		$p++;

	endwhile;

	$stars_average = dt3_rating_average( $array_stars );

	return $stars_average;

}

// Average of quality
function dt3_rating_quality_average ( $loop ) {

	$array_quality = array();
	$p = 0;

	while ( $loop->have_posts() ) : 
		$loop->the_post();

		$array_quality[ $p ] = dt3_rating_get_field( 'dt3_rating_quality' );

		$p++;

	endwhile;

	$quality_average = dt3_rating_average( $array_quality );

	return $quality_average;

}

// Average of features
function dt3_rating_features_average ( $loop ) {

	$array_features = array();
	$p = 0;

	while ( $loop->have_posts() ) : 
		$loop->the_post();

		$array_features[ $p ] = dt3_rating_get_field( 'dt3_rating_features' );

		$p++;

	endwhile;

	$features_average = dt3_rating_average( $array_features );

	return $features_average;

}

// Average of atribute
// Receives the $loop, the avalition item and return the average of item.
function dt3_rating_attribute_average ( $loop, $attr = 'dt3_rating_stars' ) {

	$attr_array = array();
	$p = 0;

	while ( $loop->have_posts() ) : 
		$loop->the_post();

		$attr_array[ $p ] = dt3_rating_get_field( $attr );

		$p++;

	endwhile;

	$attr_average = dt3_rating_average( $attr_array );

	return $attr_average;
}

// Total of avaliations
// Receives the loop and returns the total of avaliations
function dt3_rating_total ( $loop,  $product_post_id = '5' ) {

	$p = 0;

	while ( $loop->have_posts() ) :	$loop->the_post();

		$p++;

	endwhile;

	$p += dt3_rating_woo_reviews( $product_post_id );

	$total_rate = $p;

	return $total_rate;

}

// Star Percent
// Receives one rate, the total and returns the percent of rate
// function dt3_rating_percent ( $star, $total ) {
function dt3_rating_percent ( $star, $loop, $product_post_id = '5' ) {

	$total = dt3_rating_total( $loop, $product_post_id );
	$stars = 0;
	$star_counter = 0;
	$percent = 0;
	$decimal = 0;
	$array_stars = array();
	$p = 0;

	while ( $loop->have_posts() ) : 
		
		$loop->the_post();
		$star_counter = intval ( dt3_rating_get_field( 'dt3_rating_stars' ) );
		$star_alone = intval ( dt3_rating_get_field( 'dt3_rating_stars' ) );

		if ( $star == $star_counter) {

			$p++;

		}

	endwhile;

	// Total de de uma certa estrela atribuida pelo plugin dt3_rating
	$stars = $p;

	$stars += intval ( dt3_rating_woo_count_star( $star, $product_post_id ) );
	// $stars += dt3_rating_woo_reviews( $product_post_id );

	// Verify total
	if ( 0 < $total ) {
		
		$decimal = $stars / $total;
		$percent = round($decimal, 2) * 100;
		$percent = $percent . '%';

		// $percent = $stars;

	} else {

		$percent = NULL;

	}

	return $percent;

}

// The recommendations
// Show the recommendation text and icons in the loop. 
function dt3_rating_the_recommendation () {

    $rating_recommendations = dt3_rating_get_field( 'dt3_rating_recomendations' );

    if ( 'yes' == $rating_recommendations ) {

		echo '<img src="'. PLUGIN_URL .'/dt3-rating/images/circle-with-check-symbol.svg" alt="">';
		echo '<div class="recommended-text"> Recomendaria para um amigo </div>';

    } else {

		echo '<img class="icon-negative" src="'. PLUGIN_URL .'/dt3-rating/images/negative.svg" alt="">';
		echo '<div class="recommended-text"> Não recomendaria para um amigo </div>';

    }
	
}

// Get recommendation
// Returns a single recomendation as a string in the loop. 
function dt3_rating_get_recommendation () {

    $rating_recommendations = dt3_rating_get_field( 'dt3_rating_recomendations' );

    // return $rating_recommendations;

    // $recommendation_string = '';

    if ( 'yes' == $rating_recommendations ) {

		$recommendation_string = '<img src="'. PLUGIN_URL .'/dt3-rating/images/circle-with-check-symbol.svg" alt="">';
		$recommendation_string .= '<div class="recommended-text"> Recomendaria para um amigo </div>';
    	
    	return $recommendation_string;

    } else {

		$recommendation_string = '<img class="icon-negative" src="'. PLUGIN_URL .'/dt3-rating/images/negative.svg" alt="">';
		$recommendation_string .= '<div class="recommended-text"> Não recomendaria para um amigo </div>';
    	
    	return $recommendation_string;

    }

	
}

// One remendation
// Verify if one client have recommended the product
function dt3_rating_have_recommended () {

	// Get the recommendations
	$rating_recommendations = dt3_rating_get_field( 'dt3_rating_recomendations' );

	// Count the positive recommendations
	if ( 'yes' == $rating_recommendations ) {
		return 1;
	} else {
		return 0;
	}

}

// All recommendations
// Receives the loop and returns the number of clients who have recommended the product
function dt3_rating_get_recommendations ( $loop, $product_post_id = '5' ) {

	// Recomendations with dt3_rating
	$recommended_array = array();
	$r = 0;

	while ( $loop->have_posts() ) : 
		$loop->the_post();

		$r += dt3_rating_have_recommended();

	endwhile;

	$star_i = 0;

	for ( $star_i = 3; $star_i <= 5; $star_i++ ) {
		$r += dt3_rating_woo_count_star ( $star_i, $product_post_id );
	}

	return $r;

}

/*
 * Função que recebe o post_id e retorna dados dos comentários (pode ficar no plugin)
 * Retorna array com:
 * Nome do autor
 * Data de autoria
 * Conteúdo do texto
 * Número de estrelas
 */

function dt3_return_comments( $post_id ) {
	
	$rating_array = array();
	$c = 0;

	$comments = get_comments(
		array('post_id'   => $post_id,
			  'max_depth' => 1,
			  'type'      => 'comment',
			  'status'	  => 'approve',
			  'reply_text'=> ' ',
		)
	);
	
	foreach ($comments as $comment) {

		$rating = intval( get_comment_meta( $comment->comment_ID, 'rating', true ) );
		if ( $rating && 'yes' === get_option( 'woocommerce_enable_review_rating' ) ) {

			$rating_array[ $c ][ 'rating' ]  = $rating;
			$rating_array[ $c ][ 'author' ]  = wc_get_rating_html( $rating );
			$rating_array[ $c ][ 'date' ] 	 = get_comment_date( 'j \d\e F \d\e Y', $comment->comment_ID);
			$rating_array[ $c ][ 'content' ] = $comment->comment_content;

		}
	}

	return $rating_array;

}

// Returns reviews with woocomerce
function dt3_rating_woo_reviews ( $post_id ) {

	$reviews = dt3_return_comments( $post_id );

	$total_reviews = count( $reviews );

	return $total_reviews;
	//return 5;
}

// Returns the average of woocomence reviews
function dt3_rating_woo_average ( $post_id ) {

	// Get reviews
	$reviews = dt3_return_comments( $post_id );

	$all_reviews = 0;

	$total_reviews = count( $reviews );

	// Mount one array with reviews
	foreach ($reviews as $review) {
		$all_reviews += $review[ 'rating' ];
	}

	// Calculate the average with our function
	$average_reviews = $all_reviews / $total_reviews;

	$average_reviews = round( $average_reviews, 1 );

	return $average_reviews;

}

// Returns all reviews of woocomence
function dt3_rating_woo_all_reviews ( $product_post_id ) {

	// Get reviews
	$reviews = dt3_return_comments( $product_post_id );

	$all_reviews = 0;

	// Mount one array with reviews
	foreach ($reviews as $review) {
		$all_reviews += $review[ 'rating' ];
	}

	return $all_reviews;

}

// Returns total of one star
function dt3_rating_woo_count_star ( $star, $product_post_id ) {

	// Get reviews
	$reviews = dt3_return_comments( $product_post_id );

	$star_total = 0;

	// Mount one array with reviews
	foreach ($reviews as $review) {
		if ( $review[ 'rating' ] == $star ) {
			$star_total++ ;
		}
	}

	return $star_total;

}

/*
 * Função que recebe objeto produto e retorna as estrelas e o numero de clientes que avaliaram
 * Para ser inserida em outros pontos do tema
 * Modificar para uso em temas externos
 */

function dt3_rating_get_the_stars( $product_id ) {

	$rating_product_post_id = $product_id;

	$loop_rating = new WP_Query( array( 
	    'post_type'         => 'dt3-rating',
	    'posts_per_page'    => -1,
	    'meta_key'          => 'dt3_rating_post_id',
	    'meta_value'        => $rating_product_post_id,
	));
	

	// Retorna a média dos pontos como um inteiro
	$average_rating = dt3_rating_stars_average( $loop_rating, $rating_product_post_id );
	
	// Retorna o numero total de votos
	$votes = dt3_rating_total( $loop_rating, $rating_product_post_id );

	// Exibe a média dos comentários com estrelas
	echo wc_get_rating_html( $average_rating );

	// Exibe o total de avaliações
	if ( 1 == $votes ) {
		echo ' ( <a href="#comentarios"> <span class="total-avaliations-top">'. $votes .'</span> Avaliação </a> )';
	} else {
		echo ' ( <a href="#comentarios"> <span class="total-avaliations-top">'. $votes .'</span> Avaliações </a> )';
	}
	
	// Close the query
	wp_reset_query();

}

// add_filter( 'dt3_get_the_stars', 'dt3_rating_get_the_stars', 10, 1 );

/*
 * Função que recebe objeto produto e retorna as estrelas e o numero de clientes que avaliaram
 * Para ser inserida em outros pontos do tema
 * Modificar para uso em temas externos
 * Não possui links para comentarios
 */

function dt3_rating_get_stars_avaliation ( $product_id ) {

	$rating_product_post_id = $product_id;

	$loop_rating = new WP_Query( array( 
	    'post_type'         => 'dt3-rating',
	    'posts_per_page'    => -1,
	    'meta_key'          => 'dt3_rating_post_id',
	    'meta_value'        => $rating_product_post_id,
	));
	

	// Retorna a média dos pontos como um inteiro
	$average_rating = dt3_rating_stars_average( $loop_rating, $rating_product_post_id );
	
	// Retorna o numero total de votos
	$votes = dt3_rating_total( $loop_rating, $rating_product_post_id );

	// Exibe a média dos comentários com estrelas
	echo wc_get_rating_html( $average_rating );

	// Exibe o total de avaliações
/*	if ( 1 == $votes ) {
		echo ' ( <span> '. $votes .' Avaliação </span> )';
	} else {
		echo ' ( <span href="#comentarios"> '. $votes .' </span> )';
	}*/
	
	// Close the query
	wp_reset_query();

}

