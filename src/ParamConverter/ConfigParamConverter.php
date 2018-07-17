<?php
namespace Drupal\d8_training\ParamConverter;
 
use Drupal\Core\ParamConverter\ParamConverterInterface;
use Symfony\Component\Routing\Route;
use Drupal\Core\Config\ConfigFactoryInterface;

 
class ConfigParamConverter implements ParamConverterInterface {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $config;

  /**
   * Constructs ConfigParamConverter class object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config
   *   The config factory.
   */
  public function __construct(ConfigFactoryInterface $config) {
    $this->config = $config;
  }
	public function convert($value, $definition, $name, array $defaults) {
		if ($this->config->get($value)->isNew()) {
			$response = ['message' => "Config $value doesn't exist yet."];
			return $response;
		}
		else{
		  return $this->config->get($value)->getRawData();
		}
	}
 
  public function applies($definition, $name, Route $route) {
    return (!empty($definition['type']) && $definition['type'] === 'd8_training:config_converter');
  }
}