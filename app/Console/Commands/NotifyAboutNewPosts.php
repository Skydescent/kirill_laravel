<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\User;
use App\Notifications\NotifyAboutNewPostsCommandGiven;
use Illuminate\Console\Command;

class NotifyAboutNewPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notify_about_new_posts {days : Количество дней за которые нужно собрать новые статьи}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Рассылает всем пользователям сообщение о новых статьях за период, указанный в аргументах';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $users = User::get('email');
        $posts = Post::where('published', 1)
            ->whereDate('created_at', '>', now()->subDays($this->argument('days'))->toDateString())
            ->get();
        foreach ($users as $user) {
            $user->notify(new NotifyAboutNewPostsCommandGiven($posts, $this->argument('days')));
        }

    }
}
