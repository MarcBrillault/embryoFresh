<?php

namespace App\Http\Traits;

use JoliTypo\Exception\BadRuleSetException;
use JoliTypo\Fixer;
use function Embryo\strCut;

trait TextTrait
{
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

    /**
     * @param string $text
     * @return string
     */
    private function displaytext(string $text): string
    {
        $parsedown = new \Parsedown();
        $fixer     = new Fixer($this->getFixerRules());
        $fixer->setLocale($this->getFixerLocale());
        $text = $parsedown->text($text);
        try {
            $text = $fixer->fix($text);
        } catch (BadRuleSetException $e) {
        }

        return $text;
    }

    /**
     * @return array
     */
    private function getFixerRules(): array
    {
        return $this->fixers[$this->getFixerLocale()];
    }

    /**
     * @return string
     */
    private function getFixerLocale(): string
    {
        $locale = getenv('FIXER_LOCALE', $this->defaultLocale);
        if (!array_key_exists($locale, $this->fixers)) {
            $locale = $this->defaultLocale;
        }

        return $locale;
    }

    public function cut(string $text, int $length = 200): string
    {
        return strCut($text, $length);
    }
}