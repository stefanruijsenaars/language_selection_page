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
 *   id = "type",
 *   weight = -90,
 *   name = @Translation("Type of operating mode and display"),
 *   description = @Translation("Select the operating mode and display."),
 * )
 */
class LanguageSelectionPageConditionType extends LanguageSelectionPageConditionBase implements LanguageSelectionPageConditionInterface {

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
    // Do not return any language if we use the Drupal's block method
    // to display the redirection.
    // Be aware that this will automatically assign the default language.
    if ('block' == $this->configuration['config']->get('type')) {
      return $this->block();
    }

    return $this->pass();
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form[$this->getPluginId()] = array(
      '#type' => 'select',
      '#multiple' => FALSE,
      '#default_value' => $this->configuration['config']->get('type'),
      '#options' => [
        'standalone' => 'Standalone',
        'embedded' => 'Embedded',
        'block' => 'Block',
      ],
      '#description' => $this->t(
        '<ul>
         <li><b>Standalone - Template only</b>: Display the Language Selection Page template only.</li>
         <li><b>Embedded - Template in theme</b>: Insert the Language Selection Page body as <i>$content</i> in the current theme.</li>
         <li><b>Block - In a Drupal\'s block</b>: Insert the Language Selection Page in a block <em>Language Selection Block</em>.</li>
       </ul>'
      ),
    );

    return $form;
  }

}
