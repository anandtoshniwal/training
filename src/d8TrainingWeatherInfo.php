<?php

namespace Drupal\d8_training;

use Drupal\Core\Config\ConfigFactoryInterface;
use GuzzleHttp\ClientInterface;

/**
 * Class d8TrainingWeatherInfo
 */
class d8TrainingWeatherInfo {

  /**
   * The d8_training config.
   *
   * @var \Drupal\Core\Config\Config;
   */
  protected $config;

  /**
   * An http client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $client;

  /**
   * Constructs a d8TrainingWeatherInfo object.
   *
   * @param \GuzzleHttp\ClientInterface $client
   *    An HTTP client.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config
   *   The config factory service.
   */
  public function __construct(ClientInterface $client, ConfigFactoryInterface $config) {
    $this->client = $client;
    $this->config = $config->get('d8_training.settings');
  }

  /**
   *
   */
  public function weatherData($city_name) {

    try {
      $request = $this->client->get('http://api.openweathermap.org/data/2.5/weather?q=' . $city_name . '&appid=' . $this->config->get('appid'));
      $response = $request->getBody();
      $weather_info = [];
      if (!empty($response)) {
        $response = json_decode($response);
        $weather_info['city'] = $response->name;
        $weather_info['temp'] = $response->main->temp;
        $weather_info['pressure'] = $response->main->pressure;
        $weather_info['humidity'] = $response->main->humidity;
        $weather_info['temp_min'] = $response->main->temp_min;
        $weather_info['temp_max'] = $response->main->temp_max;
        $weather_info['wind_speed'] = $response->wind->speed;
      }
      return $weather_info;
    }
    catch (RequestException $e) {
      watchdog_exception('d8_training', $e->getMessage());
      return ($this->t('Error'));
    }
  }

}
