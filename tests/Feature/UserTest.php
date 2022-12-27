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

    public function controller_basic_test()
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $this->get('/contact')->assertOk();  // status code : 200
        $this->post('/contact')->assertStatus(302);  // status code : 302
        
        $this->get('/admin/login') -> assertStatus(200);
        $this->get('/admin/blogs') -> assertStatus(302);  

        // assertSeeText : 내용에 포함되어 text를 확인
        $this->get('/admin/login') -> assertSeeText('ログイン');
        
        // assertSee : 좀 더 큰 범위의 html 코드와 같은 것들의 포함 유무를 확인
        $this->get('/admin/login') -> assertSee('ログイン');
        $this->get('/admin/login') -> assertSee('svg');
        $this->get('/admin/login') -> assertSee('h1');
        $this->get('/admin/login') -> assertSee('button');

        // assertSeeInOrder : 준비한 text가 순서대로 등장
        $this->get('/admin/login') -> assertSeeTextInOrder(['管理者ログイン','ログイン']);
    }

    // model 확인 
    public function model_basic_test()
    {
        $data = [
            'id' => 1,
            'name' => 'kim',
            'email' => 'kim@gmail.com'
        ];
        $this->assertDatabaseHas('users', $data);
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


    // // login 하지 않으면 볼수 없는 페이지
    // public function test_an_action_that_requires_authentication()
    // {
    //     for($i =0; $i < 100; $i++){
    //         $user = User::factory()->create();
    //     }

    //     $width = 200;
    //     $height = 200;

    //     $response = $this->actingAs($user)->withSession(['banned' => false])->get('/');

    //     // $response = $this->post('/admin/users/create', [
    //     //     'name' => 'Test User',
    //     //     'email' => 'test@example.com',
    //     //     'image' => UploadedFile::fake()->image('avatar.jpg', $width, $height)->size(100),
    //     //     'password' => 'password1111',
    //     //     'password_confirmation' => 'password1111',
    //     //     'introduction' => 'password1111',
    //     // ]);
    //     $response->assertStatus(200); 
    // }
}
