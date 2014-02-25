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
    LANGUAGE_SELECTION_PAGE_TEMPLATE_ONLY => 'Template only',
    LANGUAGE_SELECTION_PAGE_TEMPLATE_IN_THEME => t('Template in theme')
  );
  $form['language_selection_page_redirect_type'] = array(
    '#title' => t('Select the way the Selection Page should work'),
    '#type' => 'select',
    '#multiple' => FALSE,
    '#default_value' => variable_get('language_selection_page_redirect_type', LANGUAGE_SELECTION_PAGE_TEMPLATE_ONLY),
    '#options' => $options,
    '#description' => t('<ul><li><b>Template only</b>: Display the Language Selection Page template only.</li><li><b>Template in theme</b>: Insert the Language Selection Page body as <i>$content</i> in the current theme.</li></ul>')
  );

  $form['language_selection_page_blacklisted_paths'] = array(
    '#type' => 'textarea',
    '#title' => t('Paths blacklist'),
    '#default_value' => variable_get('language_selection_page_blacklisted_paths', ''),
    '#size' => 10,
    '#description' => t('Specify on which paths the language selection pages should be circumvented.') . '<br />'
      . t("Specify pages by using their paths. Enter one path per line. The '*' character is a wildcard. Example paths are %blog for the blog page and %blog-wildcard for every personal blog. %front is the front page.", array('%blog' => 'blog', '%blog-wildcard' => 'blog/*', '%front' => '<front>')),
  );

  $form['language_selection_page_templates'] = array(
    '#title' => t('Templating system'),
    '#type' => 'fieldset',
    '#collapsible' => TRUE,
    '#collapsed' => TRUE
  );
  $form['language_selection_page_templates']['template'] = array(
    '#title' => 'Template only: html--selection-page.tpl.php',
    '#type' => 'fieldset',
    '#collapsible' => TRUE,
    '#collapsed' => TRUE
  );
  $form['language_selection_page_templates']['template']['content'] = array(
    '#type' => 'textarea',
    '#value' => file_get_contents(drupal_get_path('module', 'system') . '/html.tpl.php'),
    '#description' => 'Copy the content of this file into your theme\'s template directory. Name it <em>html--language-selection.tpl.php</em>, the variable <em>$language_selection_page</em> is also available. Read the documentation in the <em>language-selection-page-body.tpl.php</em> file.'
  );
  $form['language_selection_page_templates']['theme'] = array(
    '#title' => 'Template in theme: language-selection-page-body.tpl.php',
    '#type' => 'fieldset',
    '#collapsible' => TRUE,
    '#collapsed' => TRUE
  );
  $form['language_selection_page_templates']['theme']['content'] = array(
    '#type' => 'textarea',
    '#value' => file_get_contents(drupal_get_path('module', 'language_selection_page') . '/themes/language-selection-page-body.tpl.php'),
    '#description' => "Copy the content of this file into your theme's template directory. Name it <em>language-selection-page-body.tpl.php</em> and be sure to read the documentation in it."
  );

  drupal_flush_all_caches();

  return system_settings_form($form);
}
