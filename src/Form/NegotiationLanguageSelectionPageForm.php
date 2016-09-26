<?php

namespace Drupal\language_selection_page\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Routing\RouteBuilderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\language\Plugin\LanguageNegotiation\LanguageNegotiationUrl;

/**
 * Configure the Language Selection Page language negotiation method for this site.
 */
class NegotiationLanguageSelectionPageForm extends ConfigFormBase {

  /**
   * The language manager.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * The route builder.
   *
   * @var \Drupal\Core\Routing\RouteBuilderInterface
   */
  protected $routeBuilder;

  /**
   * Constructs a new LanguageDeleteForm object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\Core\Language\LanguageManagerInterface $language_manager
   *   The language manager.
   * @param RouteBuilderInterface $route_builder
   *   The route builder.
   */
  public function __construct(ConfigFactoryInterface $config_factory, LanguageManagerInterface $language_manager, RouteBuilderInterface $route_builder) {
    parent::__construct($config_factory);
    $this->languageManager = $language_manager;
    $this->routeBuilder = $route_builder;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('language_manager'),
      $container->get('router.builder')
    );
  }

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
    global $base_url;
    $config = $this->config('language_selection_page.negotiation');

    $form['path'] = array(
      '#title' => $this->t('Select the path of the Language Selection Page'),
      '#type' => 'textfield',
      '#default_value' => $config->get('path'),
      '#description' => t('The path of the page displaying the Language Selection Page'),
      '#required' => TRUE,
      '#size' => 40,
      '#field_prefix' => $base_url . '/',
    );

    $form['type'] = array(
      '#title' => $this->t('Select the way the Selection Page should work'),
      '#type' => 'select',
      '#multiple' => FALSE,
      '#default_value' => $config->get('type'),
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
      )
    );

    $form['blacklisted_paths'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Paths blacklist'),
      '#default_value' => implode(PHP_EOL, $config->get('blacklisted_paths')),
      '#size' => 10,
      '#description' => $this->t('Specify on which paths the language selection pages should be circumvented.') . '<br />'
        . $this->t("Specify pages by using their paths. Enter one path per line. The '*' character is a wildcard. Example paths are %blog for the blog page and %blog-wildcard for every personal blog. %front is the front page.", array('%blog' => 'blog', '%blog-wildcard' => 'blog/*', '%front' => '<front>')),
    );

    $form['ignore_language_neutral'] = array(
      '#title' => $this->t('Ignore language neutral entities and content types.'),
      '#type' => 'checkbox',
      '#default_value' => $config->get('ignore_language_neutral'),
      '#description' => t('Do not redirect to the language selection page if the entity is language neutral or if the content do not have multilingual support.'),
    );

    $form_state->setRedirect('language.negotiation');

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('language_selection_page.negotiation')
      ->set('path', $form_state->getValue('path'))
      ->set('type', $form_state->getValue('type'))
      ->set('blacklisted_paths', array_map('trim', explode(PHP_EOL, $form_state->getValue('blacklisted_paths'))))
      ->set('ignore_language_neutral', (bool) $form_state->getValue('ignore_language_neutral'))
      ->save();

    \Drupal::cache('config')->deleteAll();
    $this->routeBuilder->rebuild();

    parent::submitForm($form, $form_state);
  }

}
