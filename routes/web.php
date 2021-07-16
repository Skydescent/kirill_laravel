<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'Public\PostsController@index');
Route::get('/statistics', 'Public\StatisticsController@index')->name('statistics.index');

Route::get('/tags/{tag}', 'Public\TagsController@index')->name('tags.cloud');
Route::resource('/tasks', 'Public\TasksController');

Route::post('/tasks/{task}/steps', 'Public\TaskStepsController@store');
Route::post('/completed-steps/{step}/{task}', 'Public\CompletedTaskStepsController@store')->name('step.complete');
Route::delete('/completed-steps/{step}/{task}', 'Public\CompletedTaskStepsController@destroy')->name('step.incomplete');


Route::resource('/posts', 'Public\PostsController');
Route::resource('/news', 'Public\NewsController')->only('index','show');

Route::view('/about', 'about')->name('about');
Route::get('/contacts', 'Public\FeedbacksController@create')->name('feedbacks.create');
Route::post('/feedbacks','Public\FeedbacksController@store')->name('feedbacks.store');
Route::get('/greeting', function () {
    return 'Hello World';
});

Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
    'middleware' => ['auth', 'permissions']
], function () {
    Route::resource('/posts', 'Admin\PostsController')->only('index', 'update', 'edit', 'destroy');
    Route::resource('/news', 'Admin\NewsController');
    Route::get('/feedbacks','Public\FeedbacksController@index')->name('feedbacks.index');
    Route::get('/reports', 'Admin\ReportsController@index')->name('reports.index');
    Route::get('/reports/{report}', 'Admin\ReportsController@make')->name('reports.make');
    Route::get('/send_report/{report}', 'Admin\ReportsController@sendReport')->name('reports.send');

});

Auth::routes();

Route::middleware('auth')->post('/companies', function () {
    $attributes = request()->validate(['name' => 'required']);
    $attributes['owner_id'] = getUserId();

    \App\Models\Company::create($attributes);
});

Route::get('/service', 'Public\PushServiceController@form');
Route::post('/service', 'Public\PushServiceController@send');

Route::middleware('auth')
    ->post('/posts/{post}/comment', 'Public\CommentsController@storePostComment')
    ->name('post.comments.store');

Route::middleware('auth')
    ->post('/news/{news}/comment', 'Public\CommentsController@storeNewsComment')
    ->name('news.comments.store');

Route::post('/chat', function () {
    broadcast(new \App\Events\ChatMessage(request('message'), cachedUser()))->toOthers();
})->middleware('auth');

Route::get('/send', function () {
    broadcast(new App\Events\EveryoneEvent());
    return response('Sent');
});

Route::get('/receiver', function () {
    return view('receiver');
});