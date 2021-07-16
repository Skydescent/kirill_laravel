<?php

use App\Contracts\Repository\UserRepositoryContract;
use App\Service\Eloquent\UsersService;
use App\Models\User;
use App\Service\Pushall;

if (!function_exists('flash')) {
    /**
     * @param $message
     * @param string $type
     */
    function flash($message, string $type = 'success')
    {
        session()->flash('message', $message);
        session()->flash('message_type', $type);
    }
}

if (!function_exists('push_all')) {

    /**
     * @param null $title
     * @param null $text
     * @return mixed
     */
    function push_all($title = null, $text = null): mixed
    {
        if (is_null($title) || is_null($text)) {
            return app(Pushall::class);
        }

        return app(Pushall::class)->send($title, $text);

    }
}

if (!function_exists('cachedUser')) {
    /**
     * @param null $id
     * @return User
     */
    function cachedUser($id = null): User
    {
        $userId = $id;

        if (!$userId) {
           $userId = getUserId();
        }

       $repository = app()->make(UserRepositoryContract::class);


        if (!$userId) return new User();

        return $repository->find($userId);
    }
}

if (!function_exists('getUserId')) {
    /**
     * @return string|null
     */
    function getUserId() : ?string
    {
        $request = request();
        if (!$request->hasSession()) return new User();
        $sessionKeys = $request->session()->all();
        foreach ($sessionKeys as $key => $value) {
            if (str_starts_with($key, 'login_web_')) {
                return $value;
            }
        }
        return null;
    }
}



