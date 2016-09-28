<?php

namespace Drupal\language_selection_page;

use Drupal\Core\Config\Config;
use Symfony\Component\HttpFoundation\Request;

/**
 * Interface for TODO.
 */
interface LanguageSelectionPageConditionInterface {

  /**
   * @param \Symfony\Component\HttpFoundation\Request $request
   * @param \Drupal\Core\Config\Config $config
   *
   * @return bool
   */
  public function evaluate(Request $request, Config $config);

}
