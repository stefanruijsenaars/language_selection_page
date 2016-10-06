<?php

namespace Drupal\language_selection_page\Plugin\LanguageSelectionPageCondition;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\language_selection_page\LanguageSelectionPageConditionBase;
use Drupal\language_selection_page\LanguageSelectionPageConditionInterface;
use Drupal\Core\Entity\ContentEntityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class for the Ignore Language Neutral plugin.
 *
 * @LanguageSelectionPageCondition(
 *   id = "ignore_neutral",
 *   weight = -40,
 *   name = @Translation("Ignore language neutral entities"),
 *   description = @Translation("Ignore entities with langcodes set to Not specified or Not applicable."),
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
    // Check if the "ignore language neutral" option is checked.
    // If so, we will check if the entity language is set to
    // LANGCODE_NOT_APPLICABLE or LANGCODE_NOT_SPECIFIED, or if the entity
    // is not translatable (such as when translation is disabled on a content
    // type).
    if ($this->configuration['ignore_neutral']) {
      // Get the first entity from the route.
      foreach (\Drupal::routeMatch()->getParameters() as $parameter) {
        if ($parameter instanceof ContentEntityInterface) {
          $entity = $parameter;
          if (!$entity->isTranslatable()) {
            return $this->block();
          }
          // @todo find out if this code will ever be executed. I guess it's never translatable in these cases?
          $langcode = $entity->language()->getId();
          if ($langcode == LanguageInterface::LANGCODE_NOT_APPLICABLE || $langcode == LanguageInterface::LANGCODE_NOT_SPECIFIED) {
            return $this->block();
          }
        }
      }
    }

    return $this->pass();
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form[$this->getPluginId()] = [
      '#title' => $this->t('Ignore language neutral entities and untranslatable entity types.'),
      '#type' => 'checkbox',
      '#default_value' => $this->configuration[$this->getPluginId()],
      '#description' => $this->t('Do not redirect to the language selection page if the entity on the page being viewed is language neutral, or if the entity type is not translatable.'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::validateConfigurationForm($form, $form_state);
    $form_state->set($this->getPluginId(), (bool) $form_state->get($this->getPluginId()));
  }

}
