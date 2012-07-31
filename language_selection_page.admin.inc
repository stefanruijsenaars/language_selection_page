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

  // $form['language_selection_page_blacklisted_paths'] = array(
  //  '#type' => 'textarea',
  //  '#title' => t('List of paths to blacklist'),
  //  '#default_value' => implode("\n", variable_get('language_selection_page_blacklisted_paths', array('admin', 'user', 'admin/*', 'admin*', 'node/add/*', 'node/*/edit'))),
  //  '#size' => 10,
  //  '#description' => t('Write on each line a path to blacklist from Language Selection Page processor')
  //);

  $form['#submit'][] = 'language_selection_page_admin_submit';

  drupal_flush_all_caches();

  return system_settings_form($form);
}

function language_selection_page_admin_validate($form, &$form_state) {
  $form_state['values']['language_selection_page_blacklisted_paths'] = explode("\n", trim($form_state['values']['language_selection_page_blacklisted_paths']));
}

function language_selection_page_admin_submit($form, &$form_state) {

}
