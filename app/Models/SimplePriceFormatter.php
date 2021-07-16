<?php

namespace App\Models;

class SimplePriceFormatter implements PriceFormatter
{

    public function format($value)
    {
        return $value . ' руб';
    }
}
