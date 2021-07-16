<?php

namespace App\Contracts\Service\News;

interface DestroyNewsServiceContract
{
    public function delete($slug);
}