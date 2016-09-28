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
 *   id = \Drupal\language_selection_page\Plugin\LanguageSelectionPageCondition\LanguageSelectionPageConditionPHPSapi::ID,
 *   weight = -100,
 *   name = @Translation("URL"),
 *   description = @Translation("Language from the URL (Path prefix or domain)."),
 * )
 */
class LanguageSelectionPageConditionPHPSapi extends LanguageSelectionPageCondition implements LanguageSelectionPageConditionInterface {

  const ID = 'php_sapi';

  /**
   * {@inheritdoc}
   */
  public function evaluate(Request $request, Config $config) {
    // Bail out when running tests on commandline.
    if (PHP_SAPI === 'cli') {
      return FALSE;
    }

    return TRUE;
  }

}
