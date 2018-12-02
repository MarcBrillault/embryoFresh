<?php

namespace App\Http\Traits;

use Jenssegers\Date\Date;

trait DateTrait
{
    use PlatformTrait;

    public function formatDate(string $date, string $format = '', bool $short = false, string $locale = ''): string
    {
        $locale = $locale ?: $this->getPlatformLocale();
        $format = $format ?: $this->getDateFormatFromLocale('', $short);
        Date::setLocale($locale);

        $date = new Date($date);

        return $date->format($format);
    }

    public function getDateFormatFromLocale(string $locale = '', bool $short = false)
    {
        $locale = $locale ?: $this->getPlatformLocale();
        switch ($locale) {
            case 'en':
                return $short ? 'F jS Y' : 'l F jS Y';
            default:
                return $short ? 'j F Y' : 'l j F Y';
        }
    }
}