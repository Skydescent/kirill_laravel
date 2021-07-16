<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TasksTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    // RefreshDatabase - необходимо чтобы данный трейт использовался
    // чтобы тестовые данные, которые будут заполнятся в БД, потом убирались
    // Терйт WithFaker - для использование автоматически генерируемых данных
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAUserCanCreateATask()
        //Метод должен называться с test
    {
        $this->withoutExceptionHandling();

        // Что - входные данные: Авторизованный пользователь
        $this->actingAs($user = User::factory()->create());

        // Когда: Приходит на страницу /tasks для создания новой задачи

        // воспользуемся фабрикой для генерации случайной задачи
        $attributes = Task::factory()->raw(['owner_id' => $user]);
        $this->post('/tasks', $attributes);

        // Что нужно получить на выходе: Запись в БД о ноовой задаче
        $this->assertDatabaseHas('tasks', $attributes);
    }

    public function testGuestMayNotCreateATask()
    {
        //При обращении незарегистрированноо пользователя,
        //должен срабатывть middlewear для редиректа на страницу login
        $this->post('/tasks')->assertRedirect('/login');
    }


}
