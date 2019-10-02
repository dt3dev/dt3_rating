<?php

namespace DT3;

class Rating {
  /**
   * Retorna o valor do campo buscado dado seu nome.
   *
   * @param string $name Nome do campo buscado
   * @return string Valor do campo buscado
   */
  public function get_field($name): ?string {
    $post_id = get_the_ID();
    $field_value = get_post_meta($post_id, $name, true);
    return $field_value;
  }

  /**
   * Mostra o valor do campo buscado dado seu nome.
   *
   * @param string $name Nome do campo buscado
   * @return void
   */
  function the_field(string $name): void {
    $post_id = get_the_ID();
    $field_data = get_post_meta( $post_id, $name, true);
    echo $field_data;
  }

  /**
   * Mostra o template HTML com número
   * de estrelas.
   */
  public function the_stars(): void {
    $rating = intval($this->get_field('dt3_rating_stars'));

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
   * @return string Template HTML
   */
  public function get_stars(): string {
    $rating = intval( $this->get_field('dt3_rating_stars'));
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
   * @param string $rate Valor, entre 1 e 5, da avaliação
   */
  function the_rate(string $rate): void {
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
  public function get_comments($post_id) {

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
   * Retorna reviews do woocommerce.
   *
   * @param string $post_id ID do post o qual se deseja obter as reviews
   * @return int Número de reviews do post
   */
  public function woo_reviews (string $post_id): int {
    $reviews = $this->get_comments($post_id);
    $total_reviews = count($reviews);

    return $total_reviews;
  }

  /**
   * Retorna todos os reviews do woocommerce.
   *
   * @param string $post_id ID do post o qual se deseja obter as reviews
   * @return int Número de reviews do post
   */
  public function woo_all_reviews ($post_id): int {
    $reviews = $this->get_comments($post_id);

    $all_reviews = array_reduce($reviews, function($total, $review): int {
      $total += $review['rating'];
      return $total;
    }, 0);

    return $all_reviews;
  }

  /**
   * Calcula o valor correspondente a média de avaliações.
   *
   * @param  array  $ratings Array contendo uma lista de avaliações
   * @param  string $post_id ID do post de onde serão listadas as avaliações
   * @return float  Média de avaliações do post
   */
  public function get_average_ratings(array $ratings = [], string $post_id = '5'): ?float {
    $total_ratings   = count($ratings);
    $total_ratings  += intval($this->woo_reviews($post_id));
    $woo_reviews     = array_sum($ratings);
    $woo_all_reviews = $this->woo_all_reviews($post_id);

    if ($total_ratings != 0) {
      $average = ($woo_reviews + $woo_all_reviews) / $total_ratings;
      $average = round($average, 1);
    } else {
      $average = null;
    }

    return $average;
  }

  /**
   * Calcula o valor correspondente a média de estrelas.
   *
   * @param  WP_Query $loop    Wordpress The Loop
   * @param  string   $post_id ID do post de onde serão listadas as avaliações
   * @return float    Média de de estrelas do post
   */
  public function get_stars_average($loop, $post_id = '5'): float {
    $array_stars = array();

    while ( $loop->have_posts() ) :
      $loop->the_post();
      $array_stars[] = $this->get_field('dt3_rating_stars');
    endwhile;

    $stars_average = $this->get_average_ratings($array_stars, $post_id);

    return $stars_average;
  }

  /**
   * Mostra o template HTML da média de estrelas.
   *
   * @param int $average Média de estrelas
   */
  function the_averarge_stars(int $average): void {
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
   * @param  WP_Query $loop Wordpress Loop
   * @return float Média de avaliações de conforto
   */
  public function get_confort_average($loop): ?float {
    $array_stars = array();

    while ($loop->have_posts()):
      $loop->the_post();
      $array_stars[] = $this->get_field('dt3_rating_confort');
    endwhile;

    $stars_average = $this->get_average_ratings($array_stars);

    return $stars_average;
  }

  /**
   * Retorna a média de avaliações de qualidade
   * de um produto.
   *
   * @param  WP_Query $loop Wordpress Loop
   * @return float Média de avaliações de qualidade
   */
  public function get_quality_average($loop): ?float {
    $array_quality = array();

    while ($loop->have_posts()):
      $loop->the_post();
      $array_quality[] = $this->get_field('dt3_rating_quality');
    endwhile;

    $quality_average = $this->get_average_ratings($array_quality);

    return $quality_average;
  }

  /**
   * Retorna a média de avaliações de funcionalidade
   * de um produto.
   *
   * @param  WP_Query $loop Wordpress Loop
   * @return float Média de avaliações de funcionalidade
   */
  public function get_features_average($loop): float {
    $array_features = array();

    while ($loop->have_posts()):
      $loop->the_post();
      $array_features[] = $this->get_field('dt3_rating_features');
    endwhile;

    $features_average = $this->get_average_ratings($array_features);

    return $features_average;
  }

  /**
   * Retorna a média de avaliações de um determinado
   * atributo de um produto.
   *
   * @param  WP_Query $loop Wordpress Loop
   * @param  string $attribute Atributo o qual se deseja calcular a média de avaliações
   * @return float Média de avaliações do atributo especificado
   */
  public function get_attribute_average($loop, string $attribute = 'dt3_rating_stars'): float {
    $attribute_array = array();

    while ($loop->have_posts()):
      $loop->the_post();
      $attribute_array[] = $this->get_field($attribute);
    endwhile;

    $attribute_average = $this->get_average_ratings($attribute_array);

    return $attribute_average;
  }

  /**
   * Retorna o número de avaliações de um post.
   *
   * @param  WP_Query $loop Wordpress Loop
   * @param  string $post_id ID do post o qual se deseja o número de avaliações
   * @return int Total de avaliações de um post
   */
  public function get_total($loop,  string $post_id = '5'): int {
    $total_rate = $loop->post_count + $this->woo_reviews($post_id);
    return $total_rate;
  }

  /**
   * Retorna a quantidade de avaliações para
   * um determinado número de estrelas.
   *
   * @param  int $star O número de estrelas o qual se deseja verificar a quantidade de avaliações
   * @param  string $post_id ID do post o qual se deseja a quantidade de avaliações com um número `$star` de estrelas
   * @return int Quantidade de avaliações com um número `$star` de estrelas
   *
   * **Exemplo**:
   * ```
   * woo_count_star(3, 3253).
   * ```
   *
   * O código acima irá retornar a quantidade de avaliações
   * com `3` estrelas do post `3253`.
   */
  public function woo_count_star(int $star, string $post_id) {
    $reviews = $this->get_comments($post_id);
    $star_total = 0;

    foreach ($reviews as $review) {
      if ($review[ 'rating' ] == $star) {
        $star_total++;
      }
    }

    return $star_total;
  }

  /**
   * Retorna o percentual de avaliações para
   * um determinado número de estrelas.
   *
   * @param  int $star O número de estrelas o qual se deseja verificar o percentual de avaliações
   * @param  WP_Query $loop Wordpress Loop
   * @param  string $post_id ID do post o qual se deseja o percentual de avaliações com um número `$star` de estrelas
   * @return int Percentual de avaliações com um número `$star` de estrelas
   *
   * **Exemplo**:
   * ```
   * get_percent(3, $loop, 3253).
   * ```
   *
   * O código acima irá retornar o percentual de avaliações
   * com `3` estrelas do post `3253`.
   */
  public function get_percent($star, $loop, $post_id = '5') {
    $total = $this->get_total($loop, $post_id);
    $stars = 0;
    $star_counter = 0;
    $percent = 0;
    $decimal = 0;
    $p = 0;

    while ($loop->have_posts()):
      $loop->the_post();
      $star_counter = intval($this->get_field('dt3_rating_stars'));

      if ($star == $star_counter) {
        $p++;
      }
    endwhile;

    // Total de de uma certa estrela atribuida pelo plugin dt3_rating
    $stars = $p;

    $stars += intval ($this->woo_count_star($star, $post_id));

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
   */
  public function the_recommendation(): void {
      $rating_recommendations = $this->get_field('dt3_rating_recomendations');

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
   */
  public function get_recommendation (): string {
      $rating_recommendations = $this->get_field('dt3_rating_recomendations');

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
   * Mostra o template HTML que exibe se
   * o cliente recomendaria o produto para
   * um amigo
   */
  public function have_recommended(): bool {
    $rating_recommendations = $this->get_field('dt3_rating_recomendations');
    return $rating_recommendations == 'yes';
  }

  /**
   * Retorna o número de pessoas que recomendaria
   * um determinado produto para um amigo.
   *
   * @param WP_Query $loop Wordpress Loop
   * @param string $post_id ID do post o qual se deseja o número de recomendações
   * @return int Número de recomendações do post solicitado
   */
  public function get_recommendations($loop, string $post_id = '5'): int {
    $recommendations = 0;

    while ($loop->have_posts()):
      $loop->the_post();
      if ($this->have_recommended()) ++$recommendations;
    endwhile;

    foreach (range(3, 5) as $star) {
      $recommendations += $this->woo_count_star($star, $post_id);
    }

    return $recommendations;
  }

  /**
   * Retorna a média de avaliações do woocommerce.
   *
   * @param string $post_id ID do post o qual se deseja a média de avaliações
   * @return float Média de avaliações do post solicitado
   */
  public function get_woo_average($post_id): float {
    $reviews = $this->get_comments($post_id);
    $all_reviews = 0;
    $total_reviews = count($reviews);

    foreach ($reviews as $review) {
      $all_reviews += $review['rating'];
    }

    $average_reviews = $all_reviews / $total_reviews;

    $average_reviews = round($average_reviews, 1);

    return $average_reviews;
  }

  /**
   * Mostra o template HTML com o número de estrelas
   * e de avaliações de um produto.
   *
   * @param string $post_id ID do post o qual se deseja mostrar esse template
   */
  public function the_ratings(string $post_id): void {
    $loop_rating = new WP_Query( array(
        'post_type'      => 'dt3-rating',
        'posts_per_page' => -1,
        'meta_key'       => 'dt3_rating_post_id',
        'meta_value'     => $post_id,
    ));

    $average_rating = $this->get_stars_average($loop_rating, $post_id);

    $votes = $this->get_total($loop_rating, $post_id);

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
  public function get_stars_avaliation($post_id) {
    $loop_rating = new WP_Query( array(
      'post_type'         => 'dt3-rating',
      'posts_per_page'    => -1,
      'meta_key'          => 'dt3_rating_post_id',
      'meta_value'        => $post_id,
    ));

    $average_rating = $this->get_stars_average($loop_rating, $post_id);

    echo wc_get_rating_html($average_rating);

    wp_reset_query();
  }
}