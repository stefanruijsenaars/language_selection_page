<?php

namespace Drupal\language_selection_page;
use Drupal\Core\Config\Config;
use Symfony\Component\HttpFoundation\Request;

/**
 * Base class for language negotiation methods.
 */
abstract class LanguageSelectionPageConditionBase implements LanguageSelectionPageConditionInterface {

  /**
   * {@inheritdoc}
   */
  public function evaluate(Request $request, Config $config) {
    return FALSE;
  }

}
