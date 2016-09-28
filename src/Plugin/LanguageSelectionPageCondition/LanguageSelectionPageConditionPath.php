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
 *   id = "path",
 *   weight = -100,
 *   name = @Translation("Language selection page path"),
 *   description = @Translation("Bails out on the language selection page itself."),
 * )
 */
class LanguageSelectionPageConditionPath extends LanguageSelectionPageConditionBase implements LanguageSelectionPageConditionInterface {

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
    $path = array_slice(explode('/', trim($this->configuration['request']->getPathInfo(), '/')), 0);

    if ($path[0] === $this->configuration['config']->get('path')) {
      return $this->block();
    }

    return $this->pass();
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    global $base_url;

    $form[$this->getPluginId()] = array(
      '#type' => 'textfield',
      '#default_value' => $this->configuration['config']->get($this->getPluginId()),
      '#description' => t('The path of the page displaying the Language Selection Page'),
      '#required' => TRUE,
      '#size' => 40,
      '#field_prefix' => $base_url . '/',
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    // Flush only if there is a change in the path.
    if ($this->configuration['config']->get($this->getPluginId()) != $form_state->getValue($this->getPluginId())) {
      \Drupal::cache('config')->deleteAll();
      \Drupal::service('router.builder')->rebuild();
    }

    parent::submitConfigurationForm($form, $form_state);
  }

}
