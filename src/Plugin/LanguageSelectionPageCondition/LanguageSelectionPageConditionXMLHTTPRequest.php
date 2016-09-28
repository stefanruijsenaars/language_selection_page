<?php

namespace Drupal\language_selection_page\Plugin\LanguageSelectionPageCondition;

use Drupal\language_selection_page\LanguageSelectionPageConditionBase;
use Drupal\language_selection_page\LanguageSelectionPageConditionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class for TODO.
 *
 * @LanguageSelectionPageCondition(
 *   id = "xml_http_request",
 *   weight = -110,
 *   name = @Translation("XML HTTP Request"),
 *   description = @Translation("Bails out when the request is an AJAX request."),
 * )
 */
class LanguageSelectionPageConditionXMLHTTPRequest extends LanguageSelectionPageConditionBase implements LanguageSelectionPageConditionInterface {

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public function evaluate() {
    if ($this->configuration['request']->isXmlHttpRequest()) {
      return $this->block();
    }

    return $this->pass();
  }

}
