<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PushServiceController extends Controller
{
    public function form(): Factory|View|Application
    {
        return view('service');
    }

    public function send(): RedirectResponse
    {
        $data = \request()->validate([
            'text' => 'required|max:80',
            'title' => 'required|max:500'
        ]);

        push_all($data['title'], $data['text']);

        //Флеш уведомление
        flash('Сообщение успешно отправлено');

        //Редирект на предыдущую страницу
        return back();
    }
}
