<?php

namespace Drupal\language_selection_page\Plugin\LanguageSelectionPageCondition;

use Drupal\Core\Config\Config;
use Drupal\language_selection_page\LanguageSelectionPageConditionBase;
use Drupal\language_selection_page\LanguageSelectionPageConditionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class for TODO.
 *
 * @LanguageSelectionPageCondition(
 *   id = "language_prefixes",
 *   weight = -110,
 *   name = @Translation("Language prefixes"),
 *   description = @Translation("Bails out when enabled languages doesn't have prefixes."),
 * )
 */
class LanguageSelectionPageConditionLanguagePrefixes extends LanguageSelectionPageConditionBase implements LanguageSelectionPageConditionInterface {

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
    $languages = \Drupal::languageManager()->getNativeLanguages();
    $language_negotiation_config = \Drupal::config('language.negotiation')->get('url');
    $prefixes = array_filter($language_negotiation_config['prefixes']);

    if (count($languages) != count($prefixes)) {
      return $this->block();
    }

    return $this->pass();
  }

}
