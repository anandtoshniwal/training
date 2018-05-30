<?php

/**
 * Created by PhpStorm.
 * User: anandtoshniwal
 * Date: 29/05/18
 * Time: 7:34 PM
 */

namespace Drupal\d8_training\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Routing\Access\AccessInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\node\NodeInterface;

/**
 * Class CustomAccessCheck.
 *
 * @package Drupal\d8_training\Access
 */
class CustomAccessCheck implements AccessInterface{

  /**
   * Checks access to the node add page for the node type.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The currently logged in account.
   *
   * @param \Drupal\node\NodeInterface $node
   *   The currently accessing node.
   *
   * @return string
   *   A \Drupal\Core\Access\AccessInterface constant value.
   */
  public function access(AccountInterface $account, NodeInterface $node) {
    if($node->getOwnerId() == $account->id()){
      // If the content Author id and current user id is same, give them access.
      return AccessResult::allowed();
    }
    return AccessResult::forbidden();
  }

}