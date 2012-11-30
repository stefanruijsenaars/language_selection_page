<?php
/**
 * @file
 * The admin page of the language selection page module.
 */
/**
 * The admin page form.
 *
 * @return array
 */
function language_selection_page_admin() {
  module_load_include('inc', 'language_selection_page', 'includes/language_selection_page.helpers');

  $options = array(
    LANGUAGE_SELECTION_PAGE_TEMPLATE_IN_THEME => t('Template in theme'),
    LANGUAGE_SELECTION_PAGE_TEMPLATE_ONLY => 'Template only'
  );
  $form['language_selection_page_redirect_type'] = array(
    '#title' => t('Select the way the Selection Page should work'),
    '#type' => 'select',
    '#multiple' => FALSE,
    '#default_value' => variable_get('language_selection_page_redirect_type', LANGUAGE_SELECTION_PAGE_TEMPLATE_ONLY),
    '#options' => $options,
    '#description' => t('<b>Template in theme</b>: Insert the Language Selection Page body as <i>$content</i> in the current theme.
                         <br/><b>Template only</b>: Display the Language Selection Page template only.
                         <br/>Create a file named <i>language_selection_page.tpl.php</i> in your theme directory if you want to override the full page.
                         <br/>Create a file named <i>language_selection_page_body.tpl.php</i> in your theme directory if you want to override the body only.')
  );

   $default_blacklist_paths = array('admin', 'user', 'admin/*', 'admin*', 'node/add/*', 'node/*/edit');
   $form['language_selection_page_blacklisted_paths'] = array(
    '#type' => 'textarea',
    '#title' => t('Paths blacklist'),
    '#default_value' => variable_get('language_selection_page_blacklisted_paths', implode(PHP_EOL, $default_blacklist_paths)),
    '#size' => 10,
    '#description' => t('Specify on which paths the language selection pages should be circumvented.') . '<br />'
       . t("Specify pages by using their paths. Enter one path per line. The '*' character is a wildcard. Example paths are %blog for the blog page and %blog-wildcard for every personal blog. %front is the front page.", array('%blog' => 'blog', '%blog-wildcard' => 'blog/*', '%front' => '<front>')),
  );

  drupal_flush_all_caches();

  return system_settings_form($form);
}
