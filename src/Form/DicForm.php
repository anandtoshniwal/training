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
   *
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
    // Using fetch function from service to pull the latest inserted record.
    $result = $this->operations->fetch();
    $form['first_name'] = [
      '#type' => 'textfield',
      '#title' => t('First name'),
      '#required' => TRUE,
      '#default_value' => (!empty($result['FirstName'])) ? $result['FirstName'] : '',
    ];
    $form['last_name'] = [
      '#type' => 'textfield',
      '#title' => t('First name'),
      '#required' => TRUE,
      '#default_value' => (!empty($result['LastName'])) ? $result['LastName'] : '',
    ];
    $form['qualification'] = [
      '#type' => 'select',
      '#title' => $this->t('Qualification'),
      '#empty_option' => '-select-',
      '#options' => [
        '1' => $this->t('U.G'),
        '2' => $this->t('P.G'),
        '3' => $this->t('Other'),
      ],
      '#default_value' => NULL,
    ];
    $form['reason'] = [
      '#type' => 'textarea',
      '#title' => t('Other reason'),
      '#states' => [
        'visible' => [
          ':input[name="qualification"]' => array('value' => '3'),
        ],
        'required' => [
          ':input[name="qualification"]' => array('value' => '3'),
        ],
      ],
    ];
    $form['country'] = [
      '#type' => 'select',
      '#title' => $this->t('Country'),
      '#empty_option' => '-select-',
      '#default_value' => NULL,
      '#options' => [
        'India' => $this->t('India'),
        'UK' => $this->t('UK'),
      ],
      '#ajax' => [
        'callback' => '::createStateOptionsList',
        'wrapper' => 'edit-states',
        'method' => 'replace',
        'effect' => 'fade',
        'event' => 'change',
        'progress' => [
          'type' => 'throbber',
          'message' => NULL,
        ],
      ],
    ];
    $form['states_wrapper'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'edit-states'],
    ];
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary',
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Using the write function from service to sotre the record.
    $this->operations->write($form_state->getValue('first_name'), $form_state->getValue('last_name'));
    $this->messenger()->addStatus($this->t('Record submitted'));
  }

  /**
   * Provides list of states as an options with respect to country value.
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return list of states as an options
   */
  public function createStateOptionsList(array $form, FormStateInterface $form_state) {
    $states = [];
    switch ($form_state->getValue('country')) {
      case 'India':
        $states = ['Maharastra' => 'Maharastra', 'Bihar' => 'Bihar', 'Punjab' => 'Punjab'];
        break;

      case 'UK':
        $states = ['Bath' => 'Bath', 'Birmingham' => 'Birmingham', 'Cambridge' => 'Cambridge'];
        break;
    }
    $form['states_wrapper']['states'] = [
      '#type' => 'select',
      '#title' => $this->t('State'),
      '#empty_option' => '- select -',
      '#options' => $states,
      '#default_value' => NULL,
    ];
    return $form['states_wrapper'];
  }

}
