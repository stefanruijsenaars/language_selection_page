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
 *   id = \Drupal\language_selection_page\Plugin\LanguageSelectionPageCondition\LanguageSelectionPageConditionIgnoreNeutral::ID,
 *   weight = -40,
 *   name = @Translation("Ignore neutral"),
 *   description = @Translation("TODO"),
 * )
 */
class LanguageSelectionPageConditionIgnoreNeutral extends LanguageSelectionPageCondition implements LanguageSelectionPageConditionInterface {

  const ID = 'ignore_neutral';

  /**
   * {@inheritdoc}
   */
  public function evaluate(Request $request, Config $config) {
    // Check if the ignore "language neutral" option is checked.
    // If so, we will check if the entity language is set to LANGUAGE_NONE.
    // Checking also for content type translation options since node can have the
    // default language set instead of LANGUAGE_NONE.
    if (TRUE == $config->get('ignore_neutral')) {
      $entity = $request->attributes->get('node');
      if (isset($entity) && (isset($entity->language) && $entity->language == LANGUAGE_NONE || variable_get('language_content_type_' . $entity->type,'') === '0')) {
        return FALSE;
      }
    }

    return TRUE;
  }

}
