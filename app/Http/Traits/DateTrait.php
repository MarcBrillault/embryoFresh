<?php

namespace App\Http\Traits;

use Jenssegers\Date\Date;

trait DateTrait
{
    use PlatformTrait;

    public function formatDate(string $date, string $format = '', string $locale = ''): string
    {
        $locale = $locale ?: $this->getPlatformLocale();
        $format = $format ?: $this->getDateFormatFromLocale();
        Date::setLocale($locale);

        $date = new Date($date);

        return $date->format($format);
    }

    public function getDateFormatFromLocale(string $locale = '')
    {
        $locale = $locale ?: $this->getPlatformLocale();
        switch ($locale) {
            case 'en':
                return 'l F jS Y';
            default:
                return 'l j F Y';
        }
    }
}