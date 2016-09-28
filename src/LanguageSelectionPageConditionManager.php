<?php

namespace Drupal\language_selection_page;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * Manages language selection page condition plugins.
 */
class LanguageSelectionPageConditionManager extends DefaultPluginManager {

  /**
   * Constructs a new LanguageSelectionPageConditionManager object.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   An object that implements CacheBackendInterface.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   An object that implements ModuleHandlerInterface.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/LanguageSelectionPageCondition', $namespaces, $module_handler, 'Drupal\language_selection_page\LanguageSelectionPageConditionInterface', 'Drupal\language_selection_page\Annotation\LanguageSelectionPageCondition');
    $this->cacheBackend = $cache_backend;
    $this->cacheKeyPrefix = 'language_selection_page_condition_plugins';
    $this->cacheKey = 'language_selection_page_condition_plugins';
    $this->alterInfo('language_selection_page_condition_info');
  }

  /**
   * {@inheritdoc}
   */
  public function getDefinitions() {
    $definitions = $this->getCachedDefinitions();
    if (!isset($definitions)) {
      $definitions = $this->findDefinitions();
      $this->setCachedDefinitions($definitions);
    }

    uasort($definitions, function ($a, $b) {
      return $a['weight'] > $b['weight'];
    });

    return $definitions;
  }

}
