<?php

namespace DT3;

class Security {
  public static function denyDirectAccess(): void {
    defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
  }
}