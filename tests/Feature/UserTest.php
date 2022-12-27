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

    public function test_controller_basic_test()
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
    public function test_model_basic_test()
    {
        $data = [
            'id' => 1,
            'name' => 'kim',
            'email' => 'kim@gmail.com'
        ];
        $missingData = [
            'id' => 999,
        ];
        $this->assertDatabaseMissing('users', $missingData);
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

    // model 활용하기 
    public function test_utilize_model_test()
    {
        $data = [
            'name' => 'jibin',
            'image' => 'users/vDzImZz0hZyqggHKbfZAJ7ln5cNnRnFZwivbIfqK.png',
            'email' => 'jibin@gmail.com',
            'introduction' => 'hi',
            'password' => '44444444'
        ];
        
        // create model (data 만들고 database에 들어간지 확인하기)
        $user = new User();
        $user -> fill($data)->save();
        $this -> assertDatabaseHas('users', $data);

        // update model (data의 이름을 NOT-DUMMY으로 바꿈)
        $user -> name = 'NOT-DUMMY';
        $user -> save();
        $this -> assertDatabaseMissing('users', $data);
        $data['name'] = 'NOT-DUMMY';
        $this->assertDatabaseHas('users', $data);

        // delete model (만든 데이터 지우기)
        $user -> delete();
        $this -> assertDatabaseMissing('users', $data);
    }


    // factory 활용하기
    public function test_factory_test()
    {
        $user = User::factory()->make();
        $user = User::factory()->create();

        // for($i =0; $i < 100; $i++){
        //     User::factory()->create();
        // }

        // $count = User::get()->count();
        // $user = User::find(rand(1, $count));
        // $data = $user->toArray();
        // print_r($data);

        // $this->assertDatabaseHas('users', $data);

        // $width = 200;
        // $height = 200;

        // $response = $this->actingAs($user)->withSession(['banned' => false])->get('/');

        // // $response = $this->post('/admin/users/create', [
        // //     'name' => 'Test User',
        // //     'email' => 'test@example.com',
        // //     'image' => UploadedFile::fake()->image('avatar.jpg', $width, $height)->size(100),
        // //     'password' => 'password1111',
        // //     'password_confirmation' => 'password1111',
        // //     'introduction' => 'password1111',
        // // ]);
        // $response->assertStatus(200); 
    }
}
