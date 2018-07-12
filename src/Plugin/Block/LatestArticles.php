<?php

namespace Drupal\d8_training\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Database\Connection;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Session\AccountInterface;


/**
 * Provides a 'latest_articles' block.
 *
 * @Block(
 *   id = "latest_articles",
 *   admin_label = @Translation("Latest articles"),
 *   category = @Translation("Info block")
 * )
 */
class LatestArticles extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

   /**
   * The database object.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  use StringTranslationTrait;


  /**
   * Constructs a new SwitchUserBlock object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
   * @param \Drupal\Core\Database\Connection $database
   *   The current user.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, AccountInterface $currentUser, Connection $database ) {
    // If you're extending a core plugin class, call its constructor.
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->currentUser = $currentUser;
    $this->database = $database;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_user'),
      $container->get('database')
    );
  }

  /**
  * {@inheritdoc}
  */
  public function blockForm($form, FormStateInterface $form_state) {
      $form = parent::blockForm($form, $form_state);
      return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Retrieving latest three articles.
    $query = $this->database->select('node_field_data', 'n')
        ->fields('n', ['nid', 'title'])
        ->condition('type', 'article', '=')
        ->orderBy('created', 'DESC')
        ->range(0, 3)
        ->execute();
    
    //Retrieving user email address.
    $markup = 'Email: ' . $this->currentUser->getEmail() . '<br>';

    foreach ($query as $key => $value) {
      $markup .=  $value->nid . ': ' . $value->title . '<br>';
      $values[] = 'node:' . $value->nid;
    }
    
    return [
    '#markup' => $markup,
    '#cache' => [
      'keys' => ['d8_training_tags_nid'],
      'tags' => $values,
      'contexts' => ['user']
      ],
    ];
  }
}
