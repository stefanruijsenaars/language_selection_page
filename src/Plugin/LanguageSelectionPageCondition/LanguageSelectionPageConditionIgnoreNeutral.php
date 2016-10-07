<?php

namespace Drupal\language_selection_page\Plugin\LanguageSelectionPageCondition;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\language_selection_page\LanguageSelectionPageConditionBase;
use Drupal\language_selection_page\LanguageSelectionPageConditionInterface;
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
   * The route match.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * LanguageSelectionPageConditionIgnoreNeutral constructor.
   *
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The Route Match object
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param array $plugin_definition
   *   The plugin implementation definition.
   */
  public function __construct(RouteMatchInterface $route_match, array $configuration, $plugin_id, array $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->routeMatch = $route_match;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $container->get('current_route_match'),
      $configuration,
      $plugin_id,
      $plugin_definition
    );
  }

  /**
   * {@inheritdoc}
   */
  public function evaluate() {
    if ($this->getConfiguration()[$this->getPluginId()] == TRUE) {

      foreach ($this->routeMatch->getParameters() as $parameter) {
        if ($parameter instanceof ContentEntityInterface) {
          if (!$parameter->isTranslatable()) {
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
      '#title' => $this->t('Ignore untranslatable (language neutral) entities.'),
      '#type' => 'checkbox',
      '#default_value' => $this->configuration[$this->getPluginId()],
      '#description' => $this->t('Do not redirect to the language selection page if the entity on the page being viewed is not translatable (such as when it is language neutral, or if the content type it belongs to is not translatable).'),
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
