<?php
/**
 * @file
 * Contains menu callback functions for pages defined in this module
 */
/**
 * Menu callback to display the selection page.
 */
function language_selection_page_selection_page() {
  $query = drupal_get_query_parameters();
  $from = array_key_exists('destination', $query) ? $query['destination'] : '<front>';
  unset($query['q']);
  unset($query['destination']);

  $language_none_object = new StdClass();
  $language_none_object->language = LANGUAGE_NONE;

  // prepare information about the link the user is coming from
  $link_text = url($from, array('query' => $query, 'language' => $language_none_object));
  $data['from_link']['from_text'] = $from;
  $data['from_link']['from_query'] = $query;
  $data['from_link']['link_text'] = url($from, array('query' => $query, 'language' => $language_none_object));
  $data['from_link']['html'] = l($link_text, $from, array('query' => $query, 'language' => $language_none_object));

  $language_enabled = language_list('enabled');
  // prepare the links per language
  foreach ($language_enabled['1'] as $prefix => $language) {
    // @TODO: is prefix check necessary?
    if (!$language->prefix) continue;
    // format a human-readable link to let a user continue in this language
    $link_text = drupal_ucfirst(drupal_strtolower(t('Continue in', array(), array('langcode' => $language->language)))) . ' ' . $language->native;
    // add this link to the data list
    $data['links']['items'][$prefix] = l($link_text, $from, array('query' => $query, 'language' => $language));
    $data['urls']['items'][$prefix] = url($from, array('query' => $query, 'language' => $language));
  }

  $data['links']['html']  = theme('item_list', $data['links']);

  // before we start processing the gathered data, we let other modules alter it
  // by letting them implement hook_language_selection_page_data_alter(&$data)
  drupal_alter('language_selection_page_data', $data);

  $content = theme('language_selection_page_body', $data);

  switch (variable_get('language_selection_page_redirect_type', LANGUAGE_SELECTION_PAGE_TEMPLATE_ONLY)) {
    case LANGUAGE_SELECTION_PAGE_TEMPLATE_IN_THEME:
      return $content;
      break;
    case LANGUAGE_SELECTION_PAGE_TEMPLATE_ONLY:
      drupal_add_css(drupal_get_path('module', 'language_selection_page') . '/themes/css/language_selection_page.css');
      $data['title'] = t("Language selection");
      $data['page']['#children'] = $content;
      $data['language_selection_page'] = $data;
      print theme('html', $data);
      exit;
  }
}
