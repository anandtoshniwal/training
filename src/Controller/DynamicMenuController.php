<?php
/**
 * Created by PhpStorm.
 * User: anandtoshniwal
 * Date: 29/05/18
 * Time: 12:14 AM
 */

namespace Drupal\d8_training\Controller;

use Drupal\Core\Controller\ControllerBase;


class DynamicMenuController extends ControllerBase{

  /**
   * Returns a render-able array for a test page.
   */
  public function content($arg1) {
    $build = [
      '#markup' => t('Hello! I am your @arg listing page.', array('@arg' => $arg1)),
    ];
    return $build;
  }

}