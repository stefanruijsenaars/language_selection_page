<?php
/**
 * @file
 * Contains menu callback functions for pages defined in this module
 */

/**
 * Menu callback to display the selection page.
 */
function language_selection_page_selection_page() {
  if (variable_get('language_selection_page_js_cookie_redirect', FALSE)) {
    // This adds a Javascript-based automatic redirect based on language in the
    // user's language cookie. This may be useful for sites using page caching
    // such as Varnish or Boost.
    $param = variable_get('language_cookie_param', 'language');
    drupal_add_js(array('language_selection_page' => array('cookieName' => $param)), 'setting');
    drupal_add_js(drupal_get_path('module', 'language_selection_page') . '/themes/js/cookie_redirect.js');
  }
  else {
    module_load_include('inc', 'language_selection_page', 'includes/language_selection_page.helpers');
    // Go to the destination directly
    // if a valid language negotiation is detected.
    // If a valid language negotiation exists, it means that the url has
    // prefix.
    // Example of path: [lang_prefix]/language_selection/[destination_segment_1]/[destination_segment_2]
    if (language_selection_page_is_negotiation_detected()) {
      // To get the destination properly we have to implode the slice of the array array with an offset of 2.
      // If no destination is detected, we redirect the user to the homepage, drupal_goto() will automatically
      // add the language properly.
      $path = language_selection_page_request_path(2);
      $destination =  isset($path[0]) ? implode('/', $path) : '<front>';

      drupal_goto($destination);
    }
  }

  // Get the array of data.
  $language_selection_page = language_selection_page_selection_page_data();

  // Convert the array of data in HTML.
  $content = theme(
    'language_selection_page_body',
    array(
      'language_selection_page' => $language_selection_page
    )
  );

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
      drupal_exit();
  }
}
