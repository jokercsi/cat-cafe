<?php


namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Providers\RouteServiceProvider;

use Symfony\Component\HttpFoundation\Response;
use App\Models\User;


class UserTest extends TestCase
{
   // use RefreshDatabase, WithFaker; ->

    // private const URL = '/api/users/sign-up';

    public function test_example()
    {
        $response0 = $this->get('/');
        $response0->assertStatus(200);

        $response1 = $this->get('/admin/login');
        $response1 ->assertStatus(200);

        $response2 = $this->get('/contact');
        $response2 ->assertStatus(200);


        # redirect to login page (if not loged in)
        $response3 = $this->get('/admin/blogs');
        $response3 ->assertStatus(302);  
    }

    // 로그인 확인
    public function test_user_login()
    {

        $response = $this->post('/admin/login', [
            'email' => 'kim@gmail.com',
            'password' => 'Jokerjoker1',
        ]);
        $response->assertStatus(302); 
    }


    // login 하지 않으면 볼수 없는 페이지
    public function test_an_action_that_requires_authentication()
    {
        $user = User::factory()->create();

        $width = 200;
        $height = 200;

        $response = $this->actingAs($user)->withSession(['banned' => false])->get('/');

        // $response = $this->post('/admin/users/create', [
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        //     'image' => UploadedFile::fake()->image('avatar.jpg', $width, $height)->size(100),
        //     'password' => 'password1111',
        //     'password_confirmation' => 'password1111',
        //     'introduction' => 'password1111',
        // ]);
        // $response->assertStatus(200); 
    }

}
