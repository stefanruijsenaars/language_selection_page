<?php

namespace Drupal\language_selection_page\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\MainContent\MainContentRendererInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class PageController.
 */
class PageController extends ControllerBase {

  /**
   * The route match service.
   *
   * @var RouteMatchInterface $currentRouteMatch
   */
  protected $currentRouteMatch;

  /**
   * The main content renderer.
   *
   * @var \Drupal\Core\Render\MainContent\MainContentRendererInterface
   */
  protected $mainContentRenderer;

  /**
   * The request stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * PageController constructor.
   *
   * @param \Drupal\Core\Routing\RouteMatchInterface $current_route_match
   *   The route match service
   * @param \Drupal\Core\Render\MainContent\MainContentRendererInterface $main_content_renderer
   *   The main content renderer
   */
  public function __construct(RouteMatchInterface $current_route_match, MainContentRendererInterface $main_content_renderer, RequestStack $request_stack) {
    $this->currentRouteMatch = $current_route_match;
    $this->mainContentRenderer = $main_content_renderer;
    $this->requestStack = $request_stack;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_route_match'),
      $container->get('main_content_renderer.html'),
      $container->get('request_stack')
    );
  }

  /**
   * Page callback.
   */
  public function main() {
    $request = $this->requestStack->getCurrentRequest();
    $languages = $this->languageManager->getLanguages();
    $config = $this->configFactory->get('language_selection_page.negotiation');

    if (!empty($request->getQueryString())) {
      list(, $destination) = explode('=', $request->getQueryString(), 2);
      $destination = urldecode($destination);
      if (empty($destination)) {
        return new RedirectResponse('/');
      }
    }
    else {
      return new RedirectResponse('/');
    }

    $links_array = [];
    foreach ($this->languageManager->getNativeLanguages() as $language) {
      $url = Url::fromUserInput($destination, ['language' => $language]);
      $links_array[$language->getId()] = [
        // We need to clone the $url object to avoid using the same one for all
        // links. When the links are rendered, options are set on the $url
        // object, so if we use the same one, they would be set for all links.
        'url' => clone $url,
        'title' => $language->getName(),
        'language' => $language,
        'attributes' => ['class' => ['language-link']],
      ];
    }

    $links = [];
    foreach ($languages as $language) {
      $url = Url::fromUserInput($destination, ['language' => $language]);
      $link = \Drupal::linkGenerator()->generate($language->getName(), $url);
      $links[$language->getId()] = $link;
    }

    $content = [
      '#theme' => 'language_selection_page_content',
      '#destination' => $destination,
      '#language_links' => [
        '#theme' => 'item_list',
        '#items' => $links,
      ],
    ];

    if ($config->get('type') == 'standalone') {
      $page = [
        '#type' => 'page',
        '#title' => $config->get('title'),
        'content' => $content,
      ];

      $response = $this->mainContentRenderer->renderResponse($page, $request, $this->currentRouteMatch);
    }

    if ($config->get('type') == 'embedded') {
      $response = $content;
    }

    return $response;
  }

}
