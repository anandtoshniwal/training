<?php

namespace Drupal\d8_training\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\d8_training\d8TrainingWeatherInfo;

/**
 * Provides a 'weather_info' block.
 *
 * @Block(
 *   id = "weather_info",
 *   admin_label = @Translation("Weather info"),
 *   category = @Translation("Info block")
 * )
 */
class weatherInfo extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The weather info service.
   *
   * @var \Drupal\d8_training\d8TrainingWeatherInfo
   */
  protected $weatherInfo;

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
   * @param $weatherInfo
   *   The weather info service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, d8TrainingWeatherInfo $weatherInfo) {
    // If you're extending a core plugin class, call its constructor.
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->weatherInfo = $weatherInfo;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('d8_training.weather_info')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {

    $form['city_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Enter City name'),
      '#size' => 60,
      '#description' => $this->t('City name to fetch weather information.'),
      '#default_value' => $this->configuration['city_name'],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {

    $this->configuration['weatherInfo_data'] = $form_state->getValue('city_name');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    $response = $this->weatherInfo->weatherData($this->configuration['weatherInfo_data']);
    if (!empty($response)) {
      return [
        '#theme' => 'weather_info_block',
        '#city' => $response['city'],
        '#temp' => $response['temp'],
        '#pressure' => $response['pressure'],
        '#humidity' => $response['humidity'],
        '#temp_min' => $response['temp_min'],
        '#temp_max' => $response['temp_max'],
        '#wind_speed' => $response['wind_speed'],
      ];
    }
  }

}
