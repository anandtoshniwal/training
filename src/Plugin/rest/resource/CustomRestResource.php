<?php

namespace Drupal\d8_training\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Drupal\rest\ModifiedResourceResponse;
use Drupal\Core\Config\ConfigFactoryInterface;
use Psr\Log\LoggerInterface;
use Drupal\Component\Utility\SafeMarkup;

/**
 * Provides a Custom Resource
 *
 * @RestResource(
 *   id = "custom_resource",
 *   label = @Translation("Custom Resource"),
 *   uri_paths = {
 *     "canonical" = "/d8_training/custom_resource",
       "create" = "/d8_training/custom_resource"
 *   }
 * )
 */
class CustomRestResource extends ResourceBase {
  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $config;


  /**
   * Constructs a Drupal\rest\Plugin\rest\resource\EntityResource object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param array $serializer_formats
   *   The available serialization formats.
   * @param \Psr\Log\LoggerInterface $logger
   *   A logger instance.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config
   *   The config factory.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, $serializer_formats, LoggerInterface $logger, ConfigFactoryInterface $config) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);
    $this->config = $config->getEditable('d8_training.settings');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get('rest'),
      $container->get('config.factory')
    );
  }

  /**
   * Responds to entity GET requests.
   * @return \Drupal\rest\ResourceResponse
   */
   public function get() {
      $response = ['data' => $this->config->getRawData()];
      return new ResourceResponse($response);
    }

  /**
   * Responds to  POST requests and saves the new config value.
   * @return \Drupal\rest\ResourceResponse
   */
  public function post(array $data){
    if (empty($data)) {
      throw new BadRequestHttpException('No data received.');
    }
    else{
      try{
        $this->config->set('appid', SafeMarkup::checkPlain($data['appid']))->save();
        return new ModifiedResourceResponse($data, 201);
      }
      catch (EntityStorageException $e) {
        throw new HttpException(500, 'Internal Server Error', $e);
      }
    }
  }
}