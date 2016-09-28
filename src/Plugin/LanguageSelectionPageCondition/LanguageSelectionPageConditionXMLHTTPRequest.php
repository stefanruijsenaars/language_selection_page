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
 *   id = \Drupal\language_selection_page\Plugin\LanguageSelectionPageCondition\LanguageSelectionPageConditionXMLHTTPRequest::ID,
 *   weight = -90,
 *   name = @Translation("URL"),
 *   description = @Translation("Language from the URL (Path prefix or domain)."),
 * )
 */
class LanguageSelectionPageConditionXMLHTTPRequest extends LanguageSelectionPageCondition implements LanguageSelectionPageConditionInterface {

  const ID = 'xml_http_request';

  /**
   * {@inheritdoc}
   */
  public function evaluate(Request $request, Config $config) {
    // Bail out when handling AJAX requests.
    if ($request->isXmlHttpRequest()) {
      return FALSE;
    }

    return TRUE;
  }

}
