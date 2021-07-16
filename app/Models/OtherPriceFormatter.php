<?php

namespace App\Models;

class OtherPriceFormatter implements PriceFormatter
{
    public function format($value)
    {
        return number_format($value, 2, '.', ' ') . ' руб.';
    }
}
