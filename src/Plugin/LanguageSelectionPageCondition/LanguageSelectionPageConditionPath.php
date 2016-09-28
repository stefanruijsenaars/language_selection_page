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
 *   id = \Drupal\language_selection_page\Plugin\LanguageSelectionPageCondition\LanguageSelectionPageConditionPath::ID,
 *   weight = -80,
 *   name = @Translation("Path"),
 *   description = @Translation("TODO"),
 * )
 */
class LanguageSelectionPageConditionPath extends LanguageSelectionPageCondition implements LanguageSelectionPageConditionInterface {

  const ID = 'path';

  /**
   * {@inheritdoc}
   */
  public function evaluate(Request $request, Config $config) {
    $path = array_slice(explode('/', trim($request->getPathInfo(), '/')), 0);

    // Don't run this code on the language selection page itself.
    if ($path[0] === $config->get('path')) {
      return FALSE;
    }

    return TRUE;
  }

}
