<?php

/**
 * Created by PhpStorm.
 * User: anandtoshniwal
 * Date: 28/05/18
 * Time: 11:55 PM
 */

namespace Drupal\d8_training\Controller;

use Drupal\Core\Controller\ControllerBase;

class StaticMenuController extends ControllerBase{

  /**
   * Returns a render-able array for a test page.
   */
  public function content() {
    $build = [
      '#markup' => t('Hello! I am your node listing page.'),
    ];
    return $build;
  }

}