<?php

namespace Drupal\language_selection_page;

use Drupal\Core\Condition\ConditionPluginBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * Base class for language selection page condition.
 */
abstract class LanguageSelectionPageConditionBase extends ConditionPluginBase implements LanguageSelectionPageConditionInterface, ContainerFactoryPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function block() {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function pass() {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function summary() {
    // TODO.
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);
    $this->configuration[$this->getPluginId()] = $form_state->getValue($this->getPluginId());
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    $definition = $this->getPluginDefinition();
    return !empty($definition['name']) ? $definition['name'] : $this->getPluginId();
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    $definition = $this->getPluginDefinition();
    return !empty($definition['description']) ? $definition['description'] : NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getWeight() {
    $definition = $this->getPluginDefinition();
    return !empty($definition['weight']) ? $definition['weight'] : 0;
  }

}
