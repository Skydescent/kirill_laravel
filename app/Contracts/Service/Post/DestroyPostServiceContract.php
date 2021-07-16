<?php

namespace App\Contracts\Service\Post;

interface DestroyPostServiceContract
{
    public function delete($slug);
}