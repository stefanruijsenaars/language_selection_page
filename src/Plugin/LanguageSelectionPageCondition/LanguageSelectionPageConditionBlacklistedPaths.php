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
 *   id = \Drupal\language_selection_page\Plugin\LanguageSelectionPageCondition\LanguageSelectionPageConditionBlacklistedPaths::ID,
 *   weight = -50,
 *   name = @Translation("Blacklisted paths"),
 *   description = @Translation("TODO"),
 * )
 */
class LanguageSelectionPageConditionBlacklistedPaths extends LanguageSelectionPageCondition implements LanguageSelectionPageConditionInterface {

  const ID = 'blacklisted_paths';

  /**
   * {@inheritdoc}
   */
  public function evaluate(Request $request, Config $config) {
    // Check the path against a list of paths where that the module shouldn't run
    // on.
    // This list of path is configurable on the admin page.
    foreach ((array) $config->get('blacklisted_paths') as $blacklisted_path) {
      $is_on_blacklisted_path = \Drupal::service('path.matcher')->matchPath($request->getRequestUri(), $blacklisted_path);
      if ($is_on_blacklisted_path) {
        return FALSE;
      }
    }

    return TRUE;
  }

}
