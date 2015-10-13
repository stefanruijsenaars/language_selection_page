<?php
/**
 * @file
 * Contains menu callback functions for pages defined in this module
 */

/**
 * Menu callback to display the selection page.
 */
function language_selection_page_selection_page() {
  $arguments = func_get_args();
  $destination = isset($arguments[0]) ? implode('/', $arguments) : '<front>';

  // If the destination passed doesn't exist or is external, set it to the
  // homepage.
  if (!drupal_valid_path($destination) || url_is_external($destination)) {
    if (!drupal_lookup_path('source', $destination)) {
      $destination = '<front>';
    }
  }

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
    if (language_selection_page_is_negotiation_detected()) {
      drupal_goto($destination);
    }
  }

  // Get the array of data.
  $language_selection_page = language_selection_page_selection_page_data($destination);

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
