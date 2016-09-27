<?php

/**
 * @file
 * Contains \Drupal\language_selection_page\Controller\PageController.
 */
namespace Drupal\language_selection_page\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\Response;

class PageController extends ControllerBase {

  /**
   * Page callback
   */
  public function main() {
    $request = \Drupal::request();
    $languages = \Drupal::languageManager()->getLanguages();
    $config = \Drupal::config('language_selection_page.negotiation');

    list(, $destination) = explode('=', $request->getQueryString(), 2);
    $destination = urldecode($destination);

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
      ]
    ];

    $output = \Drupal::service('renderer')->renderRoot($content)->__toString();

    if ($config->get('type') == 'standalone') {
      $response = new Response();
      $response->setContent($output);
    } else {
      $response = [
        '#theme' => 'language_selection_page',
        '#content' => $output,
      ];
    }

    return $response;
  }

}
