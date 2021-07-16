<?php

namespace App\Listeners;

use App\Events\PostCreated;

class SendPostCreatedNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PostCreated  $event
     * @return void
     */
    public function handle(PostCreated $event)
    {
        $post = $event->post;
        $text = "Статья: {$post->title} по ссылке: " . route('posts.show', ['post' => $post])   ;
        push_all('Новая статья в блоге', $text);
    }
}
