<?php

namespace App\Http\Traits;

use JoliTypo\Exception\BadRuleSetException;
use JoliTypo\Fixer;
use function Embryo\strCut;
use function Embryo\strIsJson;

trait TextTrait
{
    use PlatformTrait;

    private $defaultLocale = 'en_GB';
    private $fixers        = [
        'en_GB' => [
            'Ellipsis',
            'Dimension',
            'Unit',
            'Dash',
            'SmartQuotes',
            'NoSpaceBeforeComma',
            'CurlyQuote',
            'Hyphen',
            'Trademark',
        ],
        'fr_FR' => [
            'Ellipsis',
            'Dimension',
            'Unit',
            'Dash',
            'SmartQuotes',
            'FrenchNoBreakSpace',
            'NoSpaceBeforeComma',
            'CurlyQuote',
            'Hyphen',
            'Trademark',
        ],
        'fr_CA' => [
            'Ellipsis',
            'Dimension',
            'Unit',
            'Dash',
            'SmartQuotes',
            'NoSpaceBeforeComma',
            'CurlyQuote',
            'Hyphen',
            'Trademark',
        ],
        'de_DE' => [
            'Ellipsis',
            'Dimension',
            'Unit',
            'Dash',
            'SmartQuotes',
            'NoSpaceBeforeComma',
            'CurlyQuote',
            'Hyphen',
            'Trademark',
        ],
    ];

    private $additionalFixers = [
        'App\\Http\\Fixers\\MelkaFixer',
    ];

    private $localeEquivalents = [
        'fr' => 'fr_FR',
        'en' => 'en_GB',
    ];

    /**
     * @param string $text
     * @param string $locale
     * @return string
     */
    private function displaytext(string $text, string $locale = ''): string
    {
        $text = $this->extractCorrectLanguageFromJson($text);

        $parsedown = new \Parsedown();
        $fixer     = new Fixer($this->getFixerRules());
        $fixer->setLocale($this->getFixerLocale($locale));
        $text = $parsedown->text($text);
        try {
            $text = $fixer->fix($text);
        } catch (BadRuleSetException $e) {
        }

        return $text;
    }

    private function extractCorrectLanguageFromJson(string $text): string
    {
        if (strIsJson($text)) {
            $json   = json_decode($text, true);
            $locale = $this->getPlatformLocale();
            if (array_key_exists($locale, $json)) {
                $text = $json[$locale];
            } else {
                $text = array_shift($json);
            }
        }

        return $text;
    }

    /**
     * @return array
     */
    private function getFixerRules(): array
    {
        $fixerRules = $this->fixers[$this->getFixerLocale()];

        return array_merge($fixerRules, $this->additionalFixers);
    }

    /**
     * @param string $locale
     * @return string
     */
    private function getFixerLocale(string $locale = ''): string
    {
        if (!$locale || array_key_exists($locale, $this->localeEquivalents)) {
            $locale = $this->getPlatformLocale();
        }

        if (array_key_exists($locale, $this->localeEquivalents)) {
            $fixerLocale = $this->localeEquivalents[$locale];
        } else {
            $fixerLocale = $this->defaultLocale;
        }

        if (!array_key_exists($fixerLocale, $this->fixers)) {
            $fixerLocale = $this->defaultLocale;
        }

        return $fixerLocale;
    }

    /**
     * @param string $text
     * @param int    $length
     * @return string
     */
    public function cut(string $text, int $length = 200): string
    {
        return strCut($text, $length);
    }
}