<?php

class CronParser
{
    private const MONTHS = [
        'JAN' => 1,
        'FEB' => 2,
        'MAR' => 3,
        'APR' => 4,
        'MAY' => 5,
        'JUN' => 6,
        'JUL' => 7,
        'AUG' => 8,
        'SEP' => 9,
        'OCT' => 10,
        'NOV' => 11,
        'DEC' => 12
    ];

    private const WEEKDAYS = [
        'SUN' => 0,
        'MON' => 1,
        'TUE' => 2,
        'WED' => 3,
        'THU' => 4,
        'FRI' => 5,
        'SAT' => 6
    ];

    public function parseField(string $field, int $min, int $max): array
    {
        $field = strtoupper($field);

        // Nazwy miesięcy / dni tygodnia
        $field = str_replace(array_keys(self::MONTHS), array_values(self::MONTHS), $field);
        $field = str_replace(array_keys(self::WEEKDAYS), array_values(self::WEEKDAYS), $field);

        // *
        if ($field === '*') {
            return range($min, $max);
        }

        $values = [];

        // Lista: 1,2,3
        foreach (explode(',', $field) as $part) {

            // Zakres: 1-5
            if (str_contains($part, '-')) {
                [$start, $end] = explode('-', $part);
                $values = array_merge($values, range((int)$start, (int)$end));
                continue;
            }

            // Krok: */5
            if (str_contains($part, '*/')) {
                $step = (int)explode('/', $part)[1];
                for ($i = $min; $i <= $max; $i += $step) {
                    $values[] = $i;
                }
                continue;
            }

            // Pojedyncza wartość
            $values[] = (int)$part;
        }

        return array_unique($values);
    }
}
