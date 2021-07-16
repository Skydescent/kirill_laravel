<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    use RefreshDatabase;

    public function testAUserCanCreateACompany()
    {
        //Отключаем отображение других ошибок
        $this->withoutExceptionHandling();

        //Регистрируемся как новый пользователь
        $this->actingAs(User::factory()->create());

        //Отправляем запрос на страницу:
        $this->post('/companies', $attributes = ['name' => 'Qsoft']);

        //Проверяем, что в базе даных была создана запись
        $this->assertDatabaseHas('companies', $attributes);

    }

    public function testItRequiresNameOnCreate()
    {
        $this->actingAs($user = User::factory()->create());

        //посылаем POST запрос и проверяем что в сессии нет ошибок по полю name
        $this->post('/companies', [])->assertSessionHasErrors(['name']);
    }

    public function testGuestsMayNotCreateACompany()
    {
        $this->post('/companies', [])->assertRedirect('/login');
    }
}
