<?php

/**
 * @file
 * Contains \Drupal\language_selection_page\Routing\RouteController.
 */
namespace Drupal\language_selection_page\Routing;

use Symfony\Component\Routing\Route;

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
        '_title' => $config->get('page_title'),
      ],
      [
        '_permission' => 'access content',
      ]
    );

    return $routes;
  }

}
