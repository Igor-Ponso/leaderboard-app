<?php

namespace App\Support\Formatters;

class AddressFormatter
{
    /**
     * Format the address array into a string.
     *
     * @param array|null $address
     * @return string
     */
    public static function format(?array $address): string
    {
        if (!$address)
            return '';

        return implode(', ', array_filter([
            $address['street'] ?? null,
            $address['city'] ?? null,
            $address['province'] ?? null,
            $address['postal_code'] ?? null,
            'Canada',
        ]));
    }
}
