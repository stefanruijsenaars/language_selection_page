<?php

/**
 * @file
 * Contains \Drupal\language_selection_page\Controller\PageController.
 */
namespace Drupal\language_selection_page\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;

class PageController extends ControllerBase {

  public function main() {
    $request = \Drupal::request();

    $qs = $request->getQueryString();
    list($key, $value) = explode('=', $qs, 2);
    $destination = urldecode($value);

    $config = \Drupal::config('language_selection_page.negotiation');

    $content = [
      '#theme' => 'language_selection_page_content',
      '#destination' => $destination,
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
