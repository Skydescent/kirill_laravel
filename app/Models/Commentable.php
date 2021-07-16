<?php


namespace App\Models;


use Illuminate\Contracts\Routing\UrlRoutable;

interface Commentable extends UrlRoutable
{
    public function comments();
}