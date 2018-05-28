<?php
/**
 * Created by PhpStorm.
 * User: anandtoshniwal
 * Date: 29/05/18
 * Time: 12:49 AM
 */

namespace Drupal\d8_training\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\NodeInterface;

class NodeDetailController extends ControllerBase{

  /**
   * @param \Drupal\node\NodeInterface $node
   *   The node.
   *
   * @return array
   *   The render array.
   */
    public function content(NodeInterface $node) {
      return node_view($node, 'full');
    }
}