<?php

namespace Drupal\language_selection_page\Plugin\LanguageSelectionPageCondition;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Path\CurrentPathStack;
use Drupal\language_selection_page\LanguageSelectionPageConditionBase;
use Drupal\language_selection_page\LanguageSelectionPageConditionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class for the Path condition plugin.
 *
 * @LanguageSelectionPageCondition(
 *   id = "path",
 *   weight = -100,
 *   name = @Translation("Language selection page path"),
 *   description = @Translation("Set the path of the language selection page."),
 * )
 */
class LanguageSelectionPageConditionPath extends LanguageSelectionPageConditionBase implements LanguageSelectionPageConditionInterface {

  /**
   * The request stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * The current path.
   *
   * @var \Drupal\Core\Path\CurrentPathStack
   */
  protected $currentPath;

  /**
   * Constructs a LanguageSelectionPageConditionPath plugin.
   *
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   The request stack.
   * @param \Drupal\Core\Path\CurrentPathStack $current_path
   *   The current path.
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param array $plugin_definition
   *   The plugin implementation definition.
   */
  public function __construct(RequestStack $request_stack, CurrentPathStack $current_path, array $configuration, $plugin_id, array $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->requestStack = $request_stack;
    $this->currentPath = $current_path;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $container->get('request_stack'),
      $container->get('path.current'),
      $configuration,
      $plugin_id,
      $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public function evaluate() {
    $path = explode('/', trim($this->currentPath->getPath($this->requestStack->getCurrentRequest()), '/'));

    if ($path[0] === $this->configuration[$this->getPluginId()]) {
      return $this->block();
    }

    return $this->pass();
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    global $base_url;

    $form[$this->getPluginId()] = [
      '#type' => 'textfield',
      '#default_value' => $this->configuration[$this->getPluginId()],
      '#description' => t('The path of the page displaying the Language Selection Page'),
      '#required' => TRUE,
      '#size' => 40,
      '#field_prefix' => $base_url . '/',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);

    // Flush only if there is a change in the path.
    if ($this->configuration[$this->getPluginId()] != $form_state->getValue($this->getPluginId())) {
      \Drupal::cache('config')->deleteAll();
      \Drupal::service('router.builder')->rebuild();
    }
  }

}
