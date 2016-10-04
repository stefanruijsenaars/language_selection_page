<?php

namespace Drupal\language_selection_page\Plugin\LanguageNegotiation;

use Drupal\Core\Path\CurrentPathStack;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\language\LanguageNegotiationMethodBase;
use Drupal\language_selection_page\LanguageSelectionPageConditionManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class for identifying language from a language selection page.
 *
 * @LanguageNegotiation(
 *   weight = -4,
 *   name = @Translation("Language Selection Page"),
 *   description = @Translation("Language is set from a language selection page"),
 *   id = Drupal\language_selection_page\Plugin\LanguageNegotiation\LanguageNegotiationSelectionPage::METHOD_ID,
 *   config_route_name = "language.negotiation_selection_page"
 * )
 */
class LanguageNegotiationSelectionPage extends LanguageNegotiationMethodBase implements ContainerFactoryPluginInterface {

  /**
   * The language negotiation method id.
   */
  const METHOD_ID = 'language-selection-page';

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static();
  }

  /**
   * {@inheritdoc}
   */
  public function getLangcode(Request $request = NULL) {
    return FALSE;
  }

}
