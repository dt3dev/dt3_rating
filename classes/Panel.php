<?php

namespace DT3Rating;

class Panel {
  public static function init(): void {
    add_action('admin_menu', [self::class, 'admin_menu']);
    add_action('admin_init', [self::class, 'admin_init']);
  }

  public static function admin_menu(): void {
    add_options_page(
      'DT3 Rating',
      'DT3 Rating',
      'manage_options',
      'dt3-rating',
      [self::class, 'settings_page']
    );
  }

  public static function settings_page(): void {
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
      </form>
    </div>
    <?php
  }

  public static function admin_init (): void {
    register_setting(
      'dt3_rating_options',
      'dt3_rating_options',
      [
        'type'         => 'string',
        'description'  => 'dt3_rating_options description',
        'show_in_rest' => true
      ]
    );

    add_settings_section(
      'dt3_rating_main',
      'Seção 1',
      [self::class, 'section_text'],
      'dt3_rating'
    );

    add_settings_field(
      'dt3_rating_text_string',
      'DT3 Rating Text Input',
      [self::class, 'setting_string'],
      'dt3_rating',
      'dt3_rating_main'
    );
  }

  public static function section_text() {
    echo '<p>Descrição da seção.</p>';
  }

  public static function setting_string() {
    $options = get_option('dt3_rating_options');
    echo "<input id='dt3_rating_text_string' name='dt3_rating_options[text_string]' size='40' type='text' value='{$options['text_string']} ' />";
  }
}