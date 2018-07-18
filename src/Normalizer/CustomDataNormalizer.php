<?php
namespace Drupal\d8_training\Normalizer;

use Drupal\serialization\Normalizer\ContentEntityNormalizer;

class CustomDataNormalizer extends ContentEntityNormalizer{
 
  /**
 * The interface or class that this Normalizer supports.
 *
 * @var string
 */
  protected $supportedInterfaceOrClass = 'Drupal\node\NodeInterface';
/**
 * {@inheritdoc}
 */
  public function normalize($entity, $format = NULL, array $context = array()) {
    $attributes = parent::normalize($entity, $format, $context);
    foreach ($attributes as $key => $value) {
      if(isset($attributes[$key][0]['value'])){
        $attributes[$key] = $attributes[$key][0]['value'];
      }
    }
    return $attributes;
  }
}