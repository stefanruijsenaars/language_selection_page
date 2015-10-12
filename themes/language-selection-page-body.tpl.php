<?php

/**
 * @file
 * The body template file of the module language_selection_page
 *
 * Variables used:
 *  - $language_selection_page: array of informations related to this module.
 *
 *  - $language_selection_page['from'] contains an array with these properties:
 *     - text: the URL in text of the page it's coming from
 *     - query: the query of this url, if any
 *     - url: the URL in text, already generated with url()
 *     - link: the HTML link, already generated with l()
 *
 *  - $language_selection_page['links'] contains an array of arrays for each
 *    enabled language with these properties:
 *     - language: the Drupal's language object
 *     - from: the url it's coming from
 *     - query: the query parameters if any
 *     - url: the URL in text already generated with url()
 *     - link: the HTML link, already generated with l()
 */
?>

<div class="language_selection_page_body">
  <div class="language_selection_page_body_inner">
    <p>No language has been detected and you are coming from <?php print $language_selection_page['from']['link']; ?></p>

    <p>You should go to the page in:</p>

    <ul>
    <?php foreach($language_selection_page['links'] as $data): ?>
      <li><?php echo $data['link']; ?></li>
    <?php endforeach; ?>
    </ul>

    <p>This page is the default page of the module Language Selection Page, you can <a href="<?php print url('admin/config/regional/language/configure/selection_page'); ?>">configure the module</a> to alter its behavior.</p>

  </div>
</div>
