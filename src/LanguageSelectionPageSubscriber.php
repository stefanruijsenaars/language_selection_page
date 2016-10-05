<?php

namespace Drupal\language_selection_page;

use Drupal\Core\Language\LanguageInterface;
use Drupal\language_selection_page\Plugin\LanguageNegotiation\LanguageNegotiationSelectionPage;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\language\Plugin\LanguageNegotiation\LanguageNegotiationSelected;

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
   * Callback helper.
   *
   * @return array|bool
   */
  private function getLanguage() {
    $languageNegotiator = \Drupal::getContainer()->get('language_negotiator');
    // Get all methods available for this language type.
    $methods = $languageNegotiator->getNegotiationMethods(LanguageInterface::TYPE_INTERFACE);
    // @todo document why we ignore this
    unset($methods[LanguageNegotiationSelected::METHOD_ID]);
    uasort($methods, 'Drupal\Component\Utility\SortArray::sortByWeightElement');

    foreach ($methods as $method_id => $method_definition) {
      // Do not consider language providers with a lower priority than the
      // cookie language provider, nor the cookie provider itself.
      if ($method_id == LanguageNegotiationSelectionPage::METHOD_ID) {
        return FALSE;
      }
      $lang = $languageNegotiator->getNegotiationMethodInstance($method_id)->getLangcode($this->event->getRequest());
      if ($lang) {
        return $lang;
      }
    }

    // If no other language was found, use the default one.
    return \Drupal::languageManager()->getDefaultLanguage()->getId();
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
    $config = \Drupal::config('language_selection_page.negotiation');

    /** @var LanguageSelectionPageConditionManager $manager */
    $manager = \Drupal::service('plugin.manager.language_selection_page_condition');

    foreach ($manager->getDefinitions() as $def) {
      $condition_plugin = $manager->createInstance($def['id'], $config->get());
      if (!$manager->execute($condition_plugin)) {
        return FALSE;
      }
    }

    if (!$lang = $this->getLanguage()) {
      $currentPath = \Drupal::getContainer()->get('path.current');
      $request = $this->event->getRequest();

      $url = sprintf('%s%s?destination=%s', $request->getUriForPath('/'), $config->get('path'), $currentPath->getPath($request));
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
