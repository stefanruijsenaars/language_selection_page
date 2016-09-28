<?php

namespace Drupal\language_selection_page\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a language selection page condition annotation object.
 *
 * Plugin Namespace: Plugin\LanguageSelectionPageCondition
 *
 * @see plugin_api
 *
 * @Annotation
 */
class LanguageSelectionPageCondition extends Plugin {

  /**
   * The language negotiation plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The default weight of the language negotiation plugin.
   *
   * @var int
   */
  public $weight;

  /**
   * The human-readable name of the language negotiation plugin.
   *
   * @ingroup plugin_translatable
   *
   * @var \Drupal\Core\Annotation\Translation
   */
  public $name;

  /**
   * The description of the language negotiation plugin.
   *
   * @ingroup plugin_translatable
   *
   * @var \Drupal\Core\Annotation\Translation
   */
  public $description;

}
