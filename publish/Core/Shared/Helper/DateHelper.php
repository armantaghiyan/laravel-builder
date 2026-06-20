<?php

namespace App\Core\Shared\Helper;

use Carbon\Carbon;
use Hekmatinasser\Verta\Verta;

class DateHelper {

    /**
     * Default formats
     */
    protected static string $defaultDateFormat = 'Y/m/d';
    protected static string $defaultDateTimeFormat = 'Y/m/d H:i:s';

    /**
     * Smartly convert a date based on the application's active locale.
     *
     * @param mixed       $date   Carbon|string|null
     * @param string|null $format Custom format; if null, an appropriate default is chosen automatically
     *
     * @return string
     */
    public static function convert($date = null, ?string $format = null): string {
        $carbonDate = self::toCarbon($date);

        // If no format was provided, pick the default format based on whether the date has a time part
        if (is_null($format)) {
            $format = self::hasTimePart($carbonDate)
                ? self::$defaultDateTimeFormat
                : self::$defaultDateFormat;
        }

        if (app()->getLocale() === 'fa') {
            return (new Verta($carbonDate))->format($format);
        }

        return $carbonDate->format($format);
    }

    /**
     * Date only (no time)
     */
    public static function toDate($date = null): string {
        return self::convert($date, self::$defaultDateFormat);
    }

    /**
     * Date with time
     */
    public static function toDateTime($date = null): string {
        return self::convert($date, self::$defaultDateTimeFormat);
    }

    /**
     * Convert the given input to a Carbon instance
     */
    protected static function toCarbon($date): Carbon {
        if ($date instanceof Carbon) {
            return $date;
        }

        return Carbon::parse($date ?? now());
    }

    /**
     * Check whether the date's time portion is non-zero (i.e. it actually carries a time value)
     */
    protected static function hasTimePart(Carbon $date): bool {
        return $date->format('H:i:s') !== '00:00:00';
    }

    /**
     * Check whether a given format string includes a time component
     */
    public static function formatHasTime(string $format): bool {
        return (bool)preg_match('/[HhGgiSsAa]/', $format);
    }
}
