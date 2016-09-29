<?php

namespace Drupal\language_selection_page\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PageController.
 */
class PageController extends ControllerBase {

  /**
   * Page callback.
   */
  public function main() {
    $request = \Drupal::request();
    $languages = \Drupal::languageManager()->getLanguages();
    $config = \Drupal::config('language_selection_page.negotiation');

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
    foreach (\Drupal::languageManager()->getNativeLanguages() as $language) {
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

    $output = \Drupal::service('renderer')->renderRoot($content)->__toString();

    if ($config->get('type') == 'standalone') {
      $response = new Response();
      $response->setContent($output);
    }
    else {
      $response = [
        '#theme' => 'language_selection_page',
        '#content' => $output,
      ];
    }

    return $response;
  }

}
