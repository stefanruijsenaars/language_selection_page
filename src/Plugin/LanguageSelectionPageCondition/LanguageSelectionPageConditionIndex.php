<?php

namespace Drupal\language_selection_page\Plugin\LanguageSelectionPageCondition;

use Drupal\language_selection_page\LanguageSelectionPageConditionBase;
use Drupal\language_selection_page\LanguageSelectionPageConditionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class for TODO.
 *
 * @LanguageSelectionPageCondition(
 *   id = "index",
 *   weight = -60,
 *   name = @Translation("Index"),
 *   description = @Translation("Bails out when running the script on another php file than index.php."),
 * )
 */
class LanguageSelectionPageConditionIndex extends LanguageSelectionPageConditionBase implements LanguageSelectionPageConditionInterface {

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
    if ($_SERVER['SCRIPT_NAME'] !== $GLOBALS['base_path'] . 'index.php') {
      return $this->block();
    }

    return $this->pass();
  }

}
