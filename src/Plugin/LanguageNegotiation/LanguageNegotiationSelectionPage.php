<?php

namespace Drupal\language_selection_page\Plugin\LanguageNegotiation;

use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\Core\Path\CurrentPathStack;
use Drupal\Core\PathProcessor\PathProcessorManager;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\AdminContext;
use Drupal\Core\Routing\StackedRouteMatchInterface;
use Drupal\language\LanguageNegotiationMethodBase;
use Drupal\language_selection_page\LanguageSelectionPageConditionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;

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
   * The current path.
   *
   * @var \Drupal\Core\Path\CurrentPathStack
   */
  protected $currentPath;

  /**
   * Constructs a new LanguageNegotiationSelectionPage instance.
   *
   * @param \Drupal\Core\Path\CurrentPathStack $current_path
   *   The current path.
   */
  public function __construct(CurrentPathStack $current_path) {
    $this->currentPath = $current_path;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $container->get('path.current')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getLangcode(Request $request = NULL) {
    $config = \Drupal::config('language_selection_page.negotiation');
    /** @var PluginManagerInterface $manager */
    $manager = \Drupal::service('plugin.manager.language_selection_page_condition');
    $request_path = $this->currentPath->getPath($request);


    foreach ($manager->getDefinitions() as $def) {
      /** @var LanguageSelectionPageConditionInterface $condition_plugin */
      $condition_plugin = $manager->createInstance($def['id'], $config->get());
      if (!$condition_plugin->evaluate()) {
        return FALSE;
      }
    }

    // Don't run this code if we are accessing anything in the files path.
    /*
     * TODO: Files detection
    $public_files_path = variable_get('file_public_path', conf_path() . '/files');
    if (strpos($request_path, $public_files_path) === 0) {
    return FALSE;
    }
     */

    /*
     * TODO: Check if this is still valid.
    if (strpos($request_path, 'cdn/farfuture') === 0) {
    return FALSE;
    }

    if (strpos($request_path, 'httprl_async_function_callback') === 0) {
    return FALSE;
    }
     */

    // Todo: Is there a better way to do this ?
    // Redirect to the language selection page properly.
    $url = sprintf('%s%s?destination=%s', $request->getUriForPath('/'), $config->get('path'), $request_path);
    header("Location: $url");
    die();

    // Todo: Check if this is still working.
    // Todo: Patch to backport: https://www.drupal.org/node/1314384
    if (empty($GLOBALS['language']->provider)) {
      // drupal_goto($language_selection_page_url, array('absolute' => TRUE, 'language' => LANGUAGE_NONE));.
    }

    return FALSE;
  }

}
