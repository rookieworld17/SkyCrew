<?php

/**
 * Translation registry/collector component for the Site module.
 *
 * This helper component provides a lightweight in-memory registry for
 * translated strings grouped by category, along with a simple utility to
 * parse JavaScript files and extract translation usages for later
 * registration. It relies on Craft CMS' `Craft::t()` for actual translation
 * resolution.
 *
 * @package modules\site\base
 * @since 2025-11-28
 */

namespace modules\site\base;

use yii\base\Component;

/**
 * Class Translations
 *
 * Collects translations resolved via `Craft::t()` and exposes them as a
 * category-indexed array. Useful for aggregating messages used across PHP and
 * front-end code (via simple parsing of JS files) to facilitate extraction or
 * export.
 */
class Translations extends Component
{
    /**
     * Collected translations indexed by category and original message.
     *
     * Structure: `array<string, array<string, string>>`
     * - key: translation category
     * - value: map of source message => translated string
     *
     * @var array<string, array<string, string>>
     */
    protected array $translations = [];

    /**
     * Register a single translated message under the given category.
     *
     * Resolves the translation immediately using `Craft::t($category, $message)`
     * and stores it in the internal registry keyed by the original message.
     *
     * @param string $category Translation category/namespace.
     * @param string $message  Source message key to translate and store.
     * @return void
     */
    public function registerTranslation(string $category, string $message): void
    {
        if (!isset($this->translations[$category])) {
            $this->translations[$category] = [];
        }

        $this->translations[$category][$message] = \Craft::t($category, $message);
    }

    /**
     * Register multiple messages for a single category.
     *
     * Each message will be resolved via `Craft::t()` and stored in the
     * registry under its original source key.
     *
     * @param string               $category Translation category.
     * @param array<int, string>   $messages List of source messages to register.
     * @return void
     */
    public function registerTranslations(string $category, array $messages): void
    {
        if (!isset($this->translations[$category])) {
            $this->translations[$category] = [];
        }

        foreach ($messages as $message){
            $this->translations[$category][$message] = \Craft::t($category, $message);
        }
    }

    /**
     * Get all collected translations.
     *
     * Returns the full registry indexed by category and original message.
     *
     * @return array<string, array<string, string>> Map of category => (message => translated string)
     */
    public function getTranslations(): array
    {
        return $this->translations;
    }

    /**
     * Parse a JavaScript file for `t('<category>', '<message>')` usages.
     *
     * This scans the provided file content with a simple regex to extract
     * pairs of category and message used in calls like:
     * `t('app', 'Some message')`
     *
     * Notes/Limitations:
     * - Matches only single-quoted arguments in the exact order `t('cat','msg')`.
     * - Does not handle escaped quotes or nested expressions.
     * - Returns unique messages per category, without performing translation.
     *
     * @param string $path Absolute or relative path to the JS file to parse.
     * @return array<string, string[]> Map of category => list of unique messages found.
     */
    public function parseJsFile(string $path): array
    {
        $content = @file_get_contents($path);

        if (!$content) {
            return [];
        }

        $parsed = preg_match_all("/t\('(\S+)', '(\S+.*)'\)/", $content, $matches);

        if (empty($matches) || !$parsed) {
            return [];
        }

        $newTranslations = [];
        for ($i = 0, $max = $parsed; $i < $max; $i++) {
            $category = $matches[1][$i];

            if (!isset($newTranslations[$category])) {
                $newTranslations[$category] = [];
            }
            $translation = $matches[2][$i];
            if (!\in_array($translation, $newTranslations[$category], true)) {
                $newTranslations[$category][] = $translation;
            }
        }

        return $newTranslations;
    }
}