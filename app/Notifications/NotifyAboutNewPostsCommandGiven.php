<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class NotifyAboutNewPostsCommandGiven extends Notification
{
    use Queueable;

    private Collection $posts;

    private int $days;

    /**
     * Create a new notification instance.
     *
     * @param Collection $posts
     * @param $days
     */
    public function __construct(Collection $posts, int $days)
    {
        $this->posts = $posts;
        $this->days = $days;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail(mixed $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Подборка статей за последние {$this->days} дней")
            ->view('mail.new-posts-index', ['posts'=> $this->posts]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray(mixed $notifiable)
    {
        return [
            //
        ];
    }

    public function receivesBroadcastNotificationOn(): string
    {
        return 'users.' . $this->id;
    }
}
