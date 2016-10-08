<?php

namespace Drupal\Tests\language_selection_page\Functional;

use Drupal\Core\Language\LanguageInterface;
use Drupal\Tests\BrowserTestBase;

/**
 * Tests that the condition plugins work.
 *
 * @group language_selection_page
 */
class TestLanguageSelectionPageCondition extends BrowserTestBase {

  /**
   * Text to assert for to determine if we are on the Language Selection Page.
   */
  const LANGUAGE_SELECTION_PAGE_TEXT = 'This page is the default page of the module Language Selection Page';

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
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $admin = $this->drupalCreateUser([], NULL, TRUE);
    $this->drupalLogin($admin);
    // Create FR.
    $this->drupalPostForm('admin/config/regional/language/add', [
      'predefined_langcode' => 'fr',
    ], 'Add language');
    // Set prefixes to en and fr.
    $this->drupalPostForm('admin/config/regional/language/detection/url', [
      'prefix[en]' => 'en',
      'prefix[fr]' => 'fr',
    ], 'Save configuration');
    // Set up URL and language selection page methods.
    $this->drupalPostForm('admin/config/regional/language/detection', [
      'language_interface[enabled][language-selection-page]' => 1,
      'language_interface[enabled][language-url]' => 1,
    ], 'Save settings');
    // Turn on content translation for pages.
    $this->drupalPostform('admin/structure/types/manage/page', ['language_configuration[content_translation]' => 1], 'Save content type');
  }

  /**
   * Test the "ignore language neutral" condition.
   */
  public function testIgnoreLanguageNeutral() {
    // Enable ignore language paths.
    $this->drupalPostForm('admin/config/regional/language/detection/language_selection_page', ['ignore_neutral' => 1], 'Save configuration');

    // Create translatable node.
    $translatable_node1 = $this->drupalCreateNode(['langcode' => 'fr']);
    $this->drupalGet('node/' . $translatable_node1->id());
    $this->assertLanguageSelectionPageLoaded();

    // Create untranslatable node.
    $untranslatable_node1 = $this->drupalCreateNode(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED]);
    $this->drupalGet('node/' . $untranslatable_node1->id());
    $this->assertLanguageSelectionPageNotLoaded();

    // Create untranslatable node.
    $untranslatable_node1 = $this->drupalCreateNode(['langcode' => LanguageInterface::LANGCODE_NOT_APPLICABLE]);
    $this->drupalGet('node/' . $untranslatable_node1->id());
    $this->assertLanguageSelectionPageNotLoaded();

<<<<<<< HEAD

=======
>>>>>>> upstream/8.x-2.x
    // Turn off translatability of the content type.
    $this->drupalPostform('admin/structure/types/manage/page', ['language_configuration[content_translation]' => 0], 'Save content type');
    $this->drupalGet('node/' . $translatable_node1->id());
    // Assert that we don't redirect anymore.
    $this->assertLanguageSelectionPageNotLoaded();
    // Turn on translatability of the content type.
    $this->drupalPostform('admin/structure/types/manage/page', ['language_configuration[content_translation]' => 1], 'Save content type');

    // Disable ignore language paths.
    $this->drupalPostForm('admin/config/regional/language/detection/language_selection_page', ['ignore_neutral' => 0], 'Save configuration');
    $this->drupalGet('node/' . $untranslatable_node1->id());
    $this->assertLanguageSelectionPageLoaded();
  }

  /**
   * Test the "Blacklisted paths" condition.
   */
  public function testBlackListedPaths() {
    $this->drupalGet('admin/config/regional/language/detection/language_selection_page');
    $this->assertSession()->responseContains('/node/add/*');
    $this->assertSession()->responseContains('/node/*/edit');
    $node = $this->drupalCreateNode(['langcode' => 'fr']);

    $this->drupalGet('node/' . $node->id());
    $this->assertLanguageSelectionPageLoaded();

    // Add node to blacklisted paths.
    $this->drupalPostForm('admin/config/regional/language/detection/language_selection_page', ['blacklisted_paths' => '/node/' . $node->id()], 'Save configuration');
    $this->drupalGet('node/' . $node->id());
    $this->assertLanguageSelectionPageNotLoaded();

    // Add node to blacklisted paths (in the middle).
    $this->drupalPostForm('admin/config/regional/language/detection/language_selection_page', ['blacklisted_paths' => '/foo\n/node/' . $node->id() . '\n/bar'], 'Save configuration');
    $this->drupalGet('node/' . $node->id());
    $this->assertLanguageSelectionPageNotLoaded();

    // Add string that contains node, but not node itself.
    $this->drupalPostForm('admin/config/regional/language/detection/language_selection_page', ['blacklisted_paths' => '/foo\n/node/' . $node->id() . '/foobar\n/bar'], 'Save configuration');
    $this->drupalGet('node/' . $node->id());
    $this->assertLanguageSelectionPageLoaded();

    // Add string that starts with node, but not node itself.
    $this->drupalPostForm('admin/config/regional/language/detection/language_selection_page', ['blacklisted_paths' => '/node/' . $node->id() . '/foobar'], 'Save configuration');
    $this->drupalGet('node/' . $node->id());
    $this->assertLanguageSelectionPageLoaded();
  }

  /**
   * Assert that the language selection page is loaded.
   */
  protected function assertLanguageSelectionPageLoaded() {
    $this->assertSession()->pageTextContains(self::LANGUAGE_SELECTION_PAGE_TEXT);
  }

  /**
   * Assert that the language selection page is not loaded.
   */
  protected function assertLanguageSelectionPageNotLoaded() {
    $this->assertSession()->pageTextNotContains(self::LANGUAGE_SELECTION_PAGE_TEXT);
  }

}
