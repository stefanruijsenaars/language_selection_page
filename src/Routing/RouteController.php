<?php

namespace Drupal\language_selection_page\Routing;

use Symfony\Component\Routing\Route;

/**
 * Class RouteController.
 */
class RouteController {

  /**
   * {@inheritdoc}
   */
  public function routes() {
    $routes = array();
    $config = \Drupal::config('language_selection_page.negotiation');

    $routes['language_selection_page'] = new Route(
      $config->get('path'),
      [
        '_controller' => '\Drupal\language_selection_page\Controller\PageController::main',
        '_title' => $config->get('title'),
      ],
      [
        '_permission' => 'access content',
      ]
    );

    return $routes;
  }

}
