<?php

namespace App\Support;

class Money
{
    /**
     * Format an amount as Vietnamese đồng. Empty string for a missing amount.
     */
    public static function format(float|int|string|null $amount): string
    {
        if ($amount === null || $amount === '') {
            return '';
        }

        return number_format((float) $amount, 0, ',', '.').' ₫';
    }
}
