<?php

namespace Drupal\language_selection_page\Annotation;

use Drupal\Core\Condition\Annotation\Condition;

/**
 * Defines a language selection page condition annotation object.
 *
 * Plugin Namespace: Plugin\LanguageSelectionPageCondition.
 *
 * @see plugin_api
 *
 * @Annotation
 */
class LanguageSelectionPageCondition extends Condition {

  /**
   * The language selection page condition plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The default weight of the language selection page condition plugin.
   *
   * @var int
   */
  public $weight;

  /**
   * The human-readable name of the language negotiation plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $name;

  /**
   * The description of the language negotiation plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $description;

}
