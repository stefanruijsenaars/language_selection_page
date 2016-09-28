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
 *   id = \Drupal\language_selection_page\Plugin\LanguageSelectionPageCondition\LanguageSelectionPageConditionLanguagePrefixes::ID,
 *   weight = -110,
 *   name = @Translation("Language prefixes"),
 *   description = @Translation("TODO"),
 * )
 */
class LanguageSelectionPageConditionLanguagePrefixes extends LanguageSelectionPageCondition implements LanguageSelectionPageConditionInterface {

  const ID = 'language_prefixes';

  /**
   * {@inheritdoc}
   */
  public function evaluate(Request $request, Config $config) {
    $languages = \Drupal::languageManager()->getNativeLanguages();
    $language_negotiation_config = \Drupal::config('language.negotiation')->get('url');
    $prefixes = array_filter($language_negotiation_config['prefixes']);

    if (count($languages) != count($prefixes)) {
      return FALSE;
    }

    return TRUE;
  }

}
