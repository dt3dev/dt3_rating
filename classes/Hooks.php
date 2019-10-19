<?php

namespace DT3Rating;

/**
 * Classe responsável por registrar hooks
 * que serão usados por desenvolvedores de
 * temas.
 *
 * Os hooks registrados nessa classe retornam
 * componentes visuais relatacionados ao plugin, que podem
 * ser usados em diversos pontos de um tema.
 *
 * @see {https://developer.wordpress.org/reference/functions/add_action/}
 * @see {https://www.php.net/manual/pt_BR/language.types.callable.php}
 */
class Hooks {
  public static function init() {
    add_action('dt3_rating_the_ratings', 'DT3Rating\Rating::the_ratings', 10, 1);
  }
}