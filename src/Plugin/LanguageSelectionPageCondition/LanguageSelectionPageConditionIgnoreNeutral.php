<?php

namespace Drupal\language_selection_page\Plugin\LanguageSelectionPageCondition;

use Drupal\Core\Form\FormStateInterface;
use Drupal\language_selection_page\LanguageSelectionPageConditionBase;
use Drupal\language_selection_page\LanguageSelectionPageConditionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class for TODO.
 *
 * @LanguageSelectionPageCondition(
 *   id = "ignore_neutral",
 *   weight = -40,
 *   name = @Translation("Ignore language neutral entities"),
 *   description = @Translation("Ignore language neutral entities and content types."),
 * )
 */
class LanguageSelectionPageConditionIgnoreNeutral extends LanguageSelectionPageConditionBase implements LanguageSelectionPageConditionInterface {

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public function evaluate() {
    // Check if the ignore "language neutral" option is checked.
    // If so, we will check if the entity language is set to LANGUAGE_NONE.
    // Checking also for content type translation options since node can have
    // the default language set instead of LANGUAGE_NONE.
    // TODO: Make this working for D8.
    if (TRUE == $this->configuration['config']->get('ignore_neutral')) {
      $entity = $this->configuration['request']->attributes->get('node');
      if (isset($entity) && (isset($entity->language) && $entity->language == LANGUAGE_NONE || variable_get('language_content_type_' . $entity->type, '') === '0')) {
        return $this->block();
      }
    }

    return $this->pass();
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form[$this->getPluginId()] = array(
      '#title' => $this->t('Ignore language neutral entities and content types.'),
      '#type' => 'checkbox',
      '#default_value' => $this->configuration['config']->get($this->getPluginId()),
      '#description' => t('Do not redirect to the language selection page if the entity is language neutral or if the content do not have multilingual support.'),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {
    $form_state->set($this->getPluginId(), (bool) $form_state->get($this->getPluginId()));
  }

}
