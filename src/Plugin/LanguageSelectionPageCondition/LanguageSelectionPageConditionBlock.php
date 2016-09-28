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
 *   id = \Drupal\language_selection_page\Plugin\LanguageSelectionPageCondition\LanguageSelectionPageConditionBlock::ID,
 *   weight = -30,
 *   name = @Translation("Block"),
 *   description = @Translation("TODO"),
 * )
 */
class LanguageSelectionPageConditionBlock extends LanguageSelectionPageCondition implements LanguageSelectionPageConditionInterface {

  const ID = 'block';

  /**
   * {@inheritdoc}
   */
  public function evaluate(Request $request, Config $config) {
    // Do not return any language if we use the Drupal's block method
    // to display the redirection.
    // Be aware that this will automatically assign the default language.
    if ('block' == $config->get('type')) {
      return FALSE;
    }

    return TRUE;
  }

}
