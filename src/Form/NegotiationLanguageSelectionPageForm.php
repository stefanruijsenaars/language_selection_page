<?php

namespace Drupal\language_selection_page\Form;

use Drupal\Core\Config\Config;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\language_selection_page\LanguageSelectionPageConditionInterface;

/**
 * Configure the Language Selection Page language negotiation method.
 */
class NegotiationLanguageSelectionPageForm extends ConfigFormBase {

  /**
   * The variable containing the conditions configuration.
   *
   * @var Config
   */
  protected $config;

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'language_negotiation_configure_language_selection_page_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['language_selection_page.negotiation'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $this->config = $this->config('language_selection_page.negotiation');
    $manager = \Drupal::service('plugin.manager.language_selection_page_condition');

    foreach ($manager->getDefinitions() as $def) {
      /** @var LanguageSelectionPageConditionInterface $condition_plugin */
      $condition_plugin = $manager->createInstance($def['id']);
      $form_state->set(['conditions', $condition_plugin->getPluginId()], $condition_plugin);

      $condition_plugin->setConfiguration($condition_plugin->getConfiguration() + (array) $this->config->get());

      $condition_form = [];
      $condition_form['#markup'] = $condition_plugin->getDescription();
      $condition_form += $condition_plugin->buildConfigurationForm([], $form_state);

      if (!empty($condition_form[$condition_plugin->getPluginId()])) {
        $condition_form['#type'] = 'details';
        $condition_form['#open'] = TRUE;
        $condition_form['#title'] = $condition_plugin->getName();
        $condition_form['#weight'] = $condition_plugin->getWeight();
        $form['conditions'][$condition_plugin->getPluginId()] = $condition_form;
      }
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);

    /** @var LanguageSelectionPageConditionInterface $condition */
    foreach ($form_state->get(['conditions']) as $condition) {
      $condition->validateConfigurationForm($form, $form_state);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    /** @var LanguageSelectionPageConditionInterface $condition */
    foreach ($form_state->get(['conditions']) as $condition) {
      $condition->submitConfigurationForm($form, $form_state);
      if (isset($condition->getConfiguration()[$condition->getPluginId()])) {
        $this->config
          ->set($condition->getPluginId(), $condition->getConfiguration()[$condition->getPluginId()]);
      }
    }

    $this->config->save();
  }

}
