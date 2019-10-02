<?php

namespace DT3;

use Philo\Blade\Blade;

class ViewEngine {
  private $blade;
  private const VIEWS_DIR = '../views';
  private const CACHE_DIR = '../cache';

  public function __constructor(): void {
    $this->blade = new Blade(self::VIEWS_DIR, self::CACHE_DIR);
  }

  public function render(string $view, array $variables = null) {
    $this->blade->view()->make($view, $variables)->render();
  }
}