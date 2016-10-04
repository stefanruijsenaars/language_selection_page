<?php

namespace Drupal\language_selection_page;

use Drupal\Core\Language\LanguageInterface;
use Drupal\language\LanguageNegotiatorInterface;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Provides a LanguageSelectionPageSubscriber.
 */
class LanguageSelectionPageSubscriber implements EventSubscriberInterface {

  /**
   * The event
   *
   * @var FilterResponseEvent
   */
  protected $event;

  /**
   * The Language Negotiator
   *
   * @var LanguageNegotiatorInterface
   */
  protected $languageNegotiator;

  /**
   * The current path.
   *
   * @var \Drupal\Core\Path\CurrentPathStack
   */
  protected $currentPath;

  /**
   * Callback helper.
   *
   * @return array|bool
   */
  private function getLanguage() {
    $methods = $this->languageNegotiator->getNegotiationMethods(LanguageInterface::TYPE_INTERFACE);
    // Disable default language.
    unset($methods['language-selected']);
    uasort($methods, 'Drupal\Component\Utility\SortArray::sortByWeightElement');

    foreach ($methods as $method_id => $method_definition) {
      $lang = $this->languageNegotiator->getNegotiationMethodInstance($method_id)->getLangcode($this->event->getRequest());
      if ($lang) {
        return [$lang, $method_id];
      }
    }

    return FALSE;
  }

  /**
   * Event callback
   *
   * @param \Symfony\Component\HttpKernel\Event\FilterResponseEvent $event
   *
   * @return bool|Response
   */
  public function redirectToLanguageSelectionPage(FilterResponseEvent $event) {
    $this->event = $event;
    $this->currentPath = \Drupal::getContainer()->get('path.current');
    $config = \Drupal::config('language_selection_page.negotiation');

    /** @var LanguageSelectionPageConditionManager $manager */
    $manager = \Drupal::service('plugin.manager.language_selection_page_condition');

    foreach ($manager->getDefinitions() as $def) {
      $condition_plugin = $manager->createInstance($def['id'], $config->get());
      if (!$manager->execute($condition_plugin)) {
        return FALSE;
      }
    }

    $this->languageNegotiator = \Drupal::getContainer()->get('language_negotiator');
    $this->languageNegotiator->setCurrentUser(\Drupal::currentUser()->getAccount());

    $this->currentPath = \Drupal::getContainer()->get('path.current');
    $request = $this->event->getRequest();

    if (!$lang = $this->getLanguage()) {
      $url = sprintf('%s%s?destination=%s', $request->getUriForPath('/'), $config->get('path'), $this->currentPath->getPath($request));
      $response = new RedirectResponse($url);

      $event->setResponse($response);
    }

    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::RESPONSE][] = array('redirectToLanguageSelectionPage', -50);
    return $events;
  }
}
