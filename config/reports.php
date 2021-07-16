<?php

return [
    'reports' => [
         'total' => [
            'job' => \App\Jobs\ModelsCountReport::class,
            'title' => 'Итого',
            'reportable' => [
                'users' => [
                    'title' => 'Пользователей',
                    'data' => \App\Models\User::class,
                ],
                'posts' => [
                    'title' => 'Статей',
                    'data' => \App\Models\Post::class,
                ],
                'news' => [
                    'title' => 'Новостей',
                    'data' => \App\Models\News::class
                ],
                'comments' => [
                    'title' => 'Комментариев',
                    'data' => \App\Models\Comment::class
                ],
                'tags' => [
                    'title' => 'Тэгов',
                    'data' => \App\Models\Tag::class,
                ],
            ],
        ]
    ],
];