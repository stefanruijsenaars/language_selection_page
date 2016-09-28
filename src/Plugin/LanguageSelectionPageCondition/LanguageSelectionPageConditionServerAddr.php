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
 *   id = \Drupal\language_selection_page\Plugin\LanguageSelectionPageCondition\LanguageSelectionPageConditionServerAddr::ID,
 *   weight = -70,
 *   name = @Translation("Server Addr check"),
 *   description = @Translation("TODO"),
 * )
 */
class LanguageSelectionPageConditionServerAddr extends LanguageSelectionPageCondition implements LanguageSelectionPageConditionInterface {

  const ID = 'server_addr';

  /**
   * {@inheritdoc}
   */
  public function evaluate(Request $request, Config $config) {
    // @TODO: document this
    if (!isset($_SERVER['SERVER_ADDR'])) {
      return FALSE;
    }

    return TRUE;
  }

}
