<?php

namespace Drupal\language_selection_page\Plugin\LanguageSelectionPageCondition;

use Drupal\Core\Config\Config;
use Drupal\language_selection_page\Annotation\LanguageSelectionPageCondition;
use Drupal\language_selection_page\LanguageSelectionPageConditionInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class for TODO
 *
 * @LanguageSelectionPageCondition(
 *   id = \Drupal\language_selection_page\Plugin\LanguageSelectionPageCondition\LanguageSelectionPageConditionIndex::ID,
 *   weight = -60,
 *   name = @Translation("Index"),
 *   description = @Translation("TODO"),
 * )
 */
class LanguageSelectionPageConditionIndex extends LanguageSelectionPageCondition implements LanguageSelectionPageConditionInterface {

  const ID = 'index';

  /**
   * {@inheritdoc}
   */
  public function evaluate(Request $request, Config $config) {
    // Don't run this code if we are accessing another php file than index.php.
    if ($_SERVER['SCRIPT_NAME'] !== $GLOBALS['base_path'] . 'index.php') {
      return FALSE;
    }

    return TRUE;
  }

}
