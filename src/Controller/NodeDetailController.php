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
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class NodeDetailController extends ControllerBase{

  /**
   * @param \Drupal\node\NodeInterface $node
   *   The node.
   *
   * @return array
   *   The render array.
   */
    public function content(NodeInterface $node) {
      // If the content Author id and current user id is same, give them access.
      if($node->getOwnerId() == $this->currentUser()->id()) {
        return node_view($node, 'full');
      }else{
        // Display access denied page.
        throw new AccessDeniedHttpException();
      }
    }

    /**
     * @param \Drupal\node\NodeInterface $node1, $node2
     *   The first node.
     *
     * @param \Drupal\node\NodeInterface $node2
     *   The second node.
     *
     * @return array
     *   The render array.
     */
    public function multipleNodes(NodeInterface $node1, NodeInterface $node2)  {
      $build = [
        '#markup' => t('Node1: @title1 </br> node2: @title2 ', array('@title1' => $node1->getTitle(), '@title2' => $node2->getTitle())),
      ];
      return $build;
    }
}