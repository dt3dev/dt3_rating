<?php

namespace DT3Rating;

class Plugin {
  public static $ACF_PATH;
  public static $ACF_URL;
  public static $PLUGIN_URL;

  public static function init(): void {
    self::$ACF_PATH   = get_stylesheet_directory() . '/acf/';
    self::$ACF_URL    = get_stylesheet_directory_uri() . '/acf/';
    self::$PLUGIN_URL = plugin_dir_url( __DIR__ );

    include_once( Plugin::$ACF_PATH . 'acf.php' );

    add_action( 'wp_ajax_dt3_acf_save_data', 'acf_form_head' );
    add_action( 'wp_ajax_nopriv_dt3_acf_save_data', 'acf_form_head' );

    add_action('init', [self::class, 'crate_post_type']);
    add_filter('acf/settings/url', [self::class, 'update_acf_settings_url']);
    add_filter('acf/settings/show_admin', [self::class, 'hide_acf_settings']);
  }

  public static function crate_post_type(): void {
    register_post_type('dt3-rating', [
      'labels' => [
        'name'               => _x('Avaliações', 'post type general name'),
        'singular_name'      => _x('Avaliação', 'post type singular name'),
        'edit_item'          => __('Editar Avaliação '),
        'search_items'       => __('Procurar Rating'),
        'not_found'          =>  __('Nada encontrado'),
        'not_found_in_trash' => __('Nada encontrado na lixeira'),
        'parent_item_colon'  => '',
      ],
      'public'          => true,
      'query_var'       => true,
      'capability_type' => 'post',
      'has_archive'     => true,
      'hierarchical'    => false,
      'menu_position'   => 25,
      'supports'        => ['title'],
    ]);
  }

  public static function update_acf_settings_url(): string {
    return self::$ACF_URL;
  }

  function hide_acf_settings(): bool {
    return false;
  }
}