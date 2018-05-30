<?php

namespace Drupal\d8_training\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use \Drupal\d8_training\d8TrainingDatabaseStorageInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form for understanding concept of DI.
 */
class DicForm extends FormBase {
  /**
   * The database connection.
   *
   * @var \Drupal\d8_training\d8TrainingDatabaseStorageInterface
   */
  protected $operations;

  /**
   * Constructs a new DicForm.
   * @param \Drupal\d8_training\d8TrainingDatabaseStorageInterface $operations
   */
  public function __construct(d8TrainingDatabaseStorageInterface $operations) {
    $this->operations = $operations;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('d8_training.database_operation')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dic_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $result = $this->operations->fetch();
    $form['first_name'] = array(
      '#type' => 'textfield',
      '#title' => t('First name'),
      '#required' => TRUE,
      '#default_value' => (!empty($result['FirstName'])) ? $result['FirstName'] : '',
    );
    $form['last_name'] = array(
      '#type' => 'textfield',
      '#title' => t('First name'),
      '#required' => TRUE,
      '#default_value' => (!empty($result['LastName'])) ? $result['LastName'] : '',
    );
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary',
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->operations->write($form_state->getValue('first_name'), $form_state->getValue('last_name'));
    $this->messenger()->addStatus($this->t('Record submitted'));
  }

}
