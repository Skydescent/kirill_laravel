<?php

namespace App\Models;

use Illuminate\Contracts\Routing\UrlRoutable;

interface Stepable extends UrlRoutable
{
    public function steps();

    public function addStep(array $attributes);
}