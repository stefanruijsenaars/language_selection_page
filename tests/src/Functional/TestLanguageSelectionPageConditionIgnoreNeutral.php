<?php

namespace Drupal\Tests\language_selection_page\Functional;

use Drupal\Core\Language\LanguageInterface;
use Drupal\Tests\BrowserTestBase;

/**
 * Tests that the language neutral conditon works.
 *
 * @group language_selection_page
 */
class TestLanguageSelectionPageConditionIgnoreNeutral extends BrowserTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'language_selection_page',
    'locale',
    'content_translation',
  ];

  /**
   * Use the standard profile.
   *
   * @var string
   */
  protected $profile = 'standard';

  /**
   * Test the ignore language neutral condition.
   */
  public function testIgnoreLanguageNeutral() {
    $admin = $this->drupalCreateUser([], NULL, TRUE);
    $this->drupalLogin($admin);
    // Create FR
    $this->drupalPostForm('admin/config/regional/language/add', [
      'predefined_langcode' => 'fr',
    ]);
    // Set prefixes to en and fr.
    $this->drupalPostForm('admin/config/regional/language/detection/url', [
      'prefix[en]' => 'en',
      'prefix[fr]' => 'fr',
    ]);
    // Set up URL and language selection page methods.
    $this->drupalPostForm('admin/config/regional/language/detection', [
      'language_interface[enabled][language-selection-page]' => 1,
      'language_interface[enabled][language-url]' => 1,
    ]);
    // Enable ignore language paths.
    $this->drupalPostForm('admin/config/regional/language/detection/language_selection_page', ['ignore_neutral' => 1]);

    // Create untranslatable node.
    $untranslatable_node1 = $this->drupalCreateNode(['language' => LanguageInterface::LANGCODE_NOT_SPECIFIED]);
    $this->drupalGet('node/' . $untranslatable_node1->id());
    // Assert that we don't redirect.
    // @todo

    // Create untranslatable node.
    $untranslatable_node1 = $this->drupalCreateNode(['language' => LanguageInterface::LANGCODE_NOT_APPLICABLE]);
    $this->drupalGet('node/' . $untranslatable_node1->id());
    // Assert that we don't redirect.
    // @todo
    
    // Create translatable node.
    $translatable_node1 = $this->drupalCreateNode(['language' => 'fr']);
    $this->drupalGet('node/' . $translatable_node1->id());
    // Assert that we redirect.
    // @todo
    
    // Turn off translatability of the content type.
    // @todo
    $this->drupalGet('node/' . $translatable_node1->id());
    // Assert that we don't redirect anymore.
    // @todo
    // Turn on translatability of the content type.
    // @todo

    // Disable ignore language paths.
    $this->drupalPostForm('admin/config/regional/language/detection/language_selection_page', ['ignore_neutral' => 0]);
    $this->drupalGet('node/' . $untranslatable_node1->id());
    // Assert that we do redirect.
    // @todo
  }

}

