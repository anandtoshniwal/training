<?php

/**
 * Created by PhpStorm.
 * User: anandtoshniwal
 * Date: 04/07/18
 * Time: 5:32 AM
 */

namespace Drupal\d8_training\Event;

use Symfony\Component\EventDispatcher\Event;
use Drupal\node\NodeInterface;

/**
 * Wraps a node insertion event for event listeners.
 */
class NodeInsertEvent extends Event {

  const NODE_INSERT = 'd8_training.node.insert';

  /**
   * Node entity.
   *
   * @var /Drupal\node\NodeInterface
   */
  protected $node;


  /**
   * NodeInsertEvent constructor.
   * @param \Drupal\node\NodeInterface $node
   */
  public function __construct(NodeInterface $node) {
    $this->node = $node;
  }

  /**
   * Get the inserted entity.
   *
   * @return \Drupal\node\NodeInterface
   */
  public function getEntity() {
    return $this->node;
  }
}