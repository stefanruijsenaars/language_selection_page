<?php
/**
 * @file
 * This file contains all the custom functions needed for the module.
 */

/**
 * Return an array with the real language count from DB and the one from vars.
 *
 * @return array
 */
function language_selection_page_check_language_count() {
  // We cannot rely on the variables from Features or system.
  $language_count = language_list('enabled');
  return array('db' => count($language_count[1]), 'vars' => variable_get('language_count', 1));
}

/**
 * Callback to check if a language has been detected.
 *
 * @return bool|mixed
 *   If no language is detected returns FALSE or the language object.
 */
function language_selection_page_is_negotiation_detected() {
  if (!isset($GLOBALS['language']->provider)) {
    return FALSE;
  }

  if (($GLOBALS['language']->provider == 'language-default')) {
    return FALSE;
  }

  return $GLOBALS['language'];
}

/**
 * Callback that fills an array for the template.
 *
 * Alter it with an alter hook: hook_language_selection_page_data_alter().
 *
 * @param string $from
 *   The path the user is coming from. This is the path the user will be
 *   proposed to go in different languages.
 *
 * @return array
 */
function language_selection_page_selection_page_data($from = '<front>') {
  $language_selection_page = array();
  $language_none_object = new StdClass();
  $language_none_object->language = LANGUAGE_NONE;

  $query = drupal_get_query_parameters();
  $url = url($from, array('query' => $query, 'language' => $language_none_object));

  unset($query['q']);

  // prepare information about the link the user is coming from
  $language_selection_page['from'] = array(
    'text' => $from,
    'query' => $query,
    'url' => $url,
    'link' => l($url, $from, array('query' => $query, 'language' => $language_none_object))
  );

  // prepare the links per language
  $language_enabled = language_list('enabled');
  foreach ($language_enabled['1'] as $language) {
    // @TODO: is prefix check necessary?
    if (!$language->prefix) continue;

    $language_selection_page['links'][] = array(
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
  drupal_alter('language_selection_page_data', $language_selection_page);

  return $language_selection_page;
}
