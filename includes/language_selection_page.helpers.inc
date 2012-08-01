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
function _language_selection_page_check_language_count() {
  // We cannot rely on the variables from Features or system.
  $language_count = language_list('enabled');
  return array('db' => count($language_count[1]), 'vars' => variable_get('language_count', 1));
}