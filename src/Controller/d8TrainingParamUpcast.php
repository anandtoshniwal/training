<?php

namespace Drupal\d8_training\Controller;

use Drupal\Core\Controller\ControllerBase;

class d8TrainingParamUpcast extends ControllerBase{

  /**
   * Returns a render-able array for a config upcast page.
   */
  public function content($config_name) {
    $build = [
	  '#theme' => 'item_list',
	  '#items' => $config_name,
    ];
    return $build;
  }
	
}