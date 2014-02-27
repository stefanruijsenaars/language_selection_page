<?php
/**
 * @file
 * Contains menu callback functions for pages defined in this module
 */

/**
 * Menu callback to display the selection page.
 */
function language_selection_page_selection_page() {
  module_load_include('inc', 'language_selection_page', 'includes/language_selection_page.helpers');

  // Get the query parameters and set the destination to '<front>' if
  // no destination is set.
  $query = drupal_get_query_parameters() + array('destination' => '<front>');

  // Go to the destination directly if
  // a valid language negotiation is detected
  // or
  // the destination parameter is the language selection page itself.
  if (language_selection_page_language_negotiation_is_detected() || $query['destination'] == variable_get('language_selection_page_path', 'language_selection')) {
    drupal_goto($query['destination']);
  }

  // Get the array of data.
  $language_selection_page = language_selection_page_selection_page_data();
  // Convert the array of data in HTML.
  $content = language_selection_page_selection_page_content($language_selection_page);

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
