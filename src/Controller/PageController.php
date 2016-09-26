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
    $config = \Drupal::config('language_selection_page.negotiation');

    $content = [
      '#theme' => 'language_selection_page_content',
      '#var1' => 'Actual value for var1',
    ];
    $output = \Drupal::service('renderer')->renderRoot($content)->__toString();

    if ($config->get('type') == 'standalone') {
      $response = new Response();
      $response->setContent($output);
    } else {
      $response = [
        '#theme' => 'language_selection_page',
        '#var1' => $output,
      ];
    }

    return $response;
  }

}
