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
  $language_selection_page = array();

  unset($query['q']);
  unset($query['destination']);

  $language_none_object = new StdClass();
  $language_none_object->language = LANGUAGE_NONE;

  // prepare information about the link the user is coming from
  $url = url($from, array('query' => $query, 'language' => $language_none_object));
  $language_selection_page['language_selection_page']['from'] = array(
    'text' => $from,
    'query' => $query,
    'url' => $url,
    'link' => l($url, $from, array('query' => $query, 'language' => $language_none_object))
  );

  // prepare the links per language
  $language_enabled = language_list('enabled');
  foreach ($language_enabled['1'] as $prefix => $language) {
    // @TODO: is prefix check necessary?
    if (!$language->prefix) continue;

    $language_selection_page['language_selection_page']['links'][] = array(
      'language' => $language,
      'from' => $from,
      'query' => $query,
      'url' => url($from, array('query' => $query, 'language' => $language)),
      'link' => l(
        $language->native,
        $from,
        array(
          'query' => $query,
          'language' => $language,
          'attributes' => array(
            'class' => array(
              'language_selection_page_link',
              'language_selection_page_link_' . drupal_html_class(drupal_clean_css_identifier($language->language))
            )
          )
        )
      )
    );
  }

  // before we start processing the gathered data, we let other modules alter it
  // by letting them implement hook_language_selection_page_data_alter(&$data)
  drupal_alter('language_selection_page_data', $language_selection_page['language_selection_page']);

  $content = theme('language_selection_page_body', $language_selection_page);

  switch (variable_get('language_selection_page_redirect_type', LANGUAGE_SELECTION_PAGE_TEMPLATE_ONLY)) {
    case LANGUAGE_SELECTION_PAGE_TEMPLATE_IN_THEME:
      return $content;
      break;
    case LANGUAGE_SELECTION_PAGE_TEMPLATE_ONLY:
      drupal_add_css(drupal_get_path('module', 'language_selection_page') . '/themes/css/language_selection_page.css');
      $html['title'] = t("Language selection");
      $html['page']['#children'] = $content;
      $html['language_selection_page'] = $language_selection_page;
      print theme('html', $html);
      exit;
  }
}
