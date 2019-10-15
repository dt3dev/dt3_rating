<?php

namespace DT3Rating;
class Rating {
  /**
   * Retorna as avaliações de um determinado post
   *
   * @param string $post_id ID do post o qual se deseja obter as avaliações
   * @return WP_Query Avaliações do post solicitado
   */
  public static function get_ratings(string $post_id) {
    return new \WP_Query(array(
      'post_type'      => 'dt3-rating',
      'posts_per_page' => -1,
      'meta_key'       => 'dt3_rating_post_id',
      'meta_value'     => $post_id,
    ));
  }

  /**
   * Retorna o valor do campo buscado dado seu nome e id do post.
   *
   * @param string $name Nome do campo buscado
   * @param string $post_id ID do post o qual se deseja o valor do campo buscado
   * @return string Valor do campo buscado
   */
  public static function get_field($name, $post_id): ?string {
    return get_post_meta($post_id, $name, true);
  }

  /**
   * Mostra o valor do campo buscado dado seu nome e id.
   *
   * @param string $name Nome do campo buscado
   * @param string $post_id ID do post
   */
  function the_field(string $post_id,string $name): void {
    $field_data = get_post_meta($post_id, $name, true);
    echo $field_data;
  }

  /**
   * Mostra o template HTML com número
   * de estrelas.
   *
   * @param string $rating_id ID da avaliação a qual se deseja mostrar as estrelas
   */
  public static function the_stars($rating_id): void {
    $rating = intval(self::get_field('dt3_rating_stars', $rating_id));

    foreach (range(1, 5) as $star) {
        if ( $star <= $rating ) {
            echo "<img src=". PLUGIN_URL ."dt3-rating/images/red-star.svg' alt='' >";
        } else {
            echo "<img src=". PLUGIN_URL ."dt3-rating/images/white-star.svg' alt='' >";
        }
    }
  }

  /**
   * Retorna o template HTML com
   * número de estrelas.
   *
   * @param string $rating_id ID da avaliação a qual se deseja obter o template
   * @return string Template HTML
   */
  public static function get_stars(string $rating_id): string {
    $rating = intval( self::get_field('dt3_rating_stars', $rating_id));
    $stars_template = '';
    foreach(range(1, 5) as $star) {
        if ($star <= $rating) {
            $stars_template .= '<img src="'. PLUGIN_URL .'dt3-rating/images/red-star.svg" alt="" >';
        } else {
            $stars_template .= '<img src="'. PLUGIN_URL .'dt3-rating/images/white-star.svg" alt="" >';
        }
    }

    return $stars_template;
  }

  /**
   * Mostra o template HTML da barra de avaliação.
   *
   * @param float $rate Valor, entre 1 e 5, da avaliação
   */
  public static function the_rate(float $rate): void {
    $rate = intval($rate);

    foreach(range(1, 5) as $rateIndex) {
        if ( $rateIndex == $rate ) {
            echo '<span class="add-color-rate"></span>';
        } else {
            echo '<span></span>';
        }
    }
  }

  /**
   * Retorna dados dos comentários de um post
   *
   * @param string $post_id ID do post
   * @return mixed Array contendo os dados do comentário.
   */
  public static function get_comments($post_id) {

    $rating_array = array();
    $c = 0;

    $comments = get_comments(
      array(
        'post_id'   => $post_id,
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

  /**
   * Calcula o valor correspondente a média de avaliações.
   *
   * @param  array  $ratings Array contendo uma lista de avaliações
   * @return float  Média de avaliações do post
   */
  public static function get_average_ratings(array $ratings = []): ?float {
    $ratings_quantity = count($ratings);
    $ratings_sum      = array_sum($ratings);

    if ($ratings_quantity != 0) {
      $average = $ratings_sum / $ratings_quantity;
      $average = round($average, 1);
    } else {
      $average = null;
    }

    return $average;
  }

  /**
   * Calcula o valor correspondente a média de estrelas.
   *
   * @param  string $post_id ID do post de onde serão listadas as avaliações
   * @return float  Média de de estrelas do post
   */
  public static function get_stars_average($post_id): float {
    $ratings = self::get_ratings($post_id);

    $stars = array_map(function($rating) {
      return self::get_field('dt3_rating_stars', $rating->ID);
    }, $ratings->posts);

    return self::get_average_ratings($stars, $post_id);
  }

  /**
   * Mostra o template HTML da média de estrelas.
   *
   * @param int $average Média de estrelas
   */
  public static function the_averarge_stars(int $average): void {
      foreach(range(1, 5) as $star) {
          if ($star <= $average) {
              echo "<img src=". PLUGIN_URL ."dt3-rating/images/red-star.svg' alt='' >";
          } else {
              echo "<img src=". PLUGIN_URL ."dt3-rating/images/white-star.svg' alt='' >";
          }
      }
  }

  /**
   * Retorna a média de avaliações de conforto
   * de um produto.
   *
   * @param  string $post_id ID do post o qual se deseja obter as avaliações de conforto
   * @return float Média de avaliações de conforto
   */
  public static function get_confort_average(string $post_id): ?float {
    $ratings = self::get_ratings($post_id);

    $quality_array = array_map(function($rating) {
      return self::get_field('dt3_rating_confort', $rating->ID);
    }, $ratings->posts);

    $stars_average = self::get_average_ratings($quality_array);

    return $stars_average;
  }

  /**
   * Retorna a média de avaliações de qualidade
   * de um produto.
   *
   * @param  string $post_id ID do post o qual se deseja obter as avaliações de qualidade
   * @return float Média de avaliações de qualidade
   */
  public static function get_quality_average(string $post_id): ?float {
    $ratings = self::get_ratings($post_id);

    $quality_array = array_map(function($rating) {
      return self::get_field('dt3_rating_quality', $rating->ID);
    }, $ratings->posts);

    $quality_average = self::get_average_ratings($quality_array);

    return $quality_average;
  }

  /**
   * Retorna a média de avaliações de funcionalidade
   * de um produto.
   *
   * @param  string $post_id ID do post o qual se deseja obter as avaliações de funcionalidade
   * @return float Média de avaliações de funcionalidade
   */
  public static function get_features_average(string $post_id): float {
    $ratings = self::get_ratings($post_id);

    $features_array = array_map(function($rating) {
      return self::get_field('dt3_rating_features', $rating->ID);
    }, $ratings->posts);

    $features_average = self::get_average_ratings($features_array);

    return $features_average;
  }

  /**
   * Retorna a média de avaliações de um determinado
   * atributo de um produto.
   *
   * @param  string $post_id ID do post o qual se deseja obter a média de avaliações.
   * @param  string $attribute Tipo de atributo o qual se deseja a média de avaliações
   * @return float Média de avaliações do atributo especificado
   */
  public static function get_attribute_average($post_id, string $attribute = 'dt3_rating_stars'): float {
    $ratings = self::get_ratings($post_id);

    $attribute_array = array_map(function($rating) use ($attribute) {
      return self::get_field($attribute, $rating->ID);
    }, $ratings->posts);

    $attribute_average = self::get_average_ratings($attribute_array);

    return $attribute_average;
  }

  /**
   * Retorna o número de avaliações de um post.
   *
   * @param  string $post_id ID do post o qual se deseja obter o número de avaliações
   * @return int Total de avaliações de um post
   */
  public static function get_total(string $post_id): int {
    $ratings = self::get_ratings($post_id);
    return $ratings->post_count;
  }

  /**
   * Retorna o percentual de avaliações para
   * um determinado número de estrelas.
   *
   * @param  int $star O número de estrelas o qual se deseja verificar o percentual de avaliações
   * @param  string $post_id ID do post o qual se deseja o percentual de avaliações com um número `$star` de estrelas
   * @return int Percentual de avaliações com um número `$star` de estrelas
   *
   * **Exemplo**:
   * ```
   * get_percent(3, 3253).
   * ```
   *
   * O código acima irá retornar o percentual de avaliações
   * com `3` estrelas do post `3253`.
   */
  public static function get_percent($star, $post_id = '5') {
    $ratings = self::get_ratings($post_id);
    $total = self::get_total($post_id);
    $stars = 0;
    $percent = 0;
    $decimal = 0;

    $stars = array_reduce($ratings->posts, function($total, $rating) use($star) {
      $star_counter = intval(self::get_field('dt3_rating_stars', $rating->ID));
      if($star == $star_counter) ++$total;
      return $total;
    }, 0);

    // Verify total
    if ( $total > 0 ) {
      $decimal = $stars / $total;
      $percent = round($decimal, 2) * 100;
      $percent = $percent . '%';
    } else {
      $percent = null;
    }

    return $percent;
  }

  /**
   * Mostra o template HTML que exibe se
   * o cliente recomendaria o produto para
   * um amigo
   *
   * @param string $rating_id ID da avaliação a qual se que mostrar o template.
   */
  public static function the_recommendation(string $rating_id): void {
      $rating_recommendations = self::get_field('dt3_rating_recomendations', $rating_id);

      if ($rating_recommendations == 'yes') {
        echo '<img src="'. PLUGIN_URL .'/dt3-rating/images/circle-with-check-symbol.svg" alt="">';
        echo '<div class="recommended-text"> Recomendaria para um amigo </div>';
      } else {
        echo '<img class="icon-negative" src="'. PLUGIN_URL .'/dt3-rating/images/negative.svg" alt="">';
        echo '<div class="recommended-text"> Não recomendaria para um amigo </div>';
      }
  }

  /**
   * Retorna o template HTML que exibe se
   * o cliente recomendaria o produto para
   * um amigo
   *
   * @param  string $post_id ID do post o qual se quer obter o template.
   * @return string Template HTML informando se o cliente recomendaria o produto
   */
  public static function get_recommendation($post_id): string {
      $rating_recommendations = self::get_field('dt3_rating_recomendations', $post_id);

      if ($rating_recommendations == 'yes') {
        $recommendation_string = '<img src="'. PLUGIN_URL .'/dt3-rating/images/circle-with-check-symbol.svg" alt="">';
        $recommendation_string .= '<div class="recommended-text"> Recomendaria para um amigo </div>';
      } else {
        $recommendation_string = '<img class="icon-negative" src="'. PLUGIN_URL .'/dt3-rating/images/negative.svg" alt="">';
        $recommendation_string .= '<div class="recommended-text"> Não recomendaria para um amigo </div>';
      }

      return $recommendation_string;
  }

  /**
   * Retorna um booleano que informa se o cliente
   * recomendaria o produto. Se `true` o prduto é recomendado,
   * caso `false` o produto não é recomendado pelo cliente.
   *
   * @param string $rating_id ID da avaliação a qual se deseja verificar se
   * o produto é recomendado
   * @return boolean Booleano que diz se o produto é recomendado ou não
   */
  public static function have_recommended(string $rating_id): bool {
    $rating_recommendations = self::get_field('dt3_rating_recomendations', $rating_id);
    return $rating_recommendations == 'yes';
  }

  /**
   * Retorna o número de pessoas que recomendaria
   * um determinado produto para um amigo.
   *
   * @param string $post_id ID do post o qual se deseja o número de recomendações
   * @return int Número de recomendações do post solicitado
   */
  public static function get_recommendations(string $post_id = '5'): int {
    $ratings = self::get_ratings($post_id);

    $recommendations = array_reduce($ratings->posts, function($total, $rating) {
      if (self::have_recommended($rating->ID)) ++$total;
      return $total;
    }, 0);

    return $recommendations;
  }

  /**
   * Mostra o template HTML com o número de estrelas
   * e de avaliações de um produto.
   *
   * @param string $post_id ID do post o qual se deseja mostrar esse template
   */
  public static function the_ratings(string $post_id): void {
    $average_rating = self::get_stars_average($post_id);

    $votes = self::get_total($post_id);

    echo wc_get_rating_html($average_rating);

    if ($votes == 1) {
      echo ' ( <a href="#comentarios"> '. $votes .' Avaliação </a> )';
    } else {
      echo ' ( <a href="#comentarios"> '. $votes .' Avaliações </a> )';
    }

    wp_reset_query();
  }

  /**
   * Mostra o template HTML com o número de
   * avaliações de um produto.
   *
   * @param string $post_id ID do post o qual se deseja mostrar esse template
   */
  public static function get_stars_avaliation($post_id) {
    $average_rating = self::get_stars_average($post_id);

    echo wc_get_rating_html($average_rating);

    wp_reset_query();
  }
}