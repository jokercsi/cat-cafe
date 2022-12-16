<?php


namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Blog;

class UserTest extends TestCase
{
    public function test_example()
    {
        $response0 = $this->get('/');
        $response0->assertStatus(200);


        $response1 = $this->get('/admin/login');
        $response1 ->assertStatus(200);


        # redirect to login page (if not loged in)
        $response2 = $this->get('/admin/blogs');
        $response2 ->assertStatus(302);

        $response3 = $this->get('/contact');
        $response3 ->assertStatus(200);

        # READ RECORD
    }
}

// https://laravel.com/docs/9.x/testing#main-content

// 1.  "./vendor/bin/phpunit"
// 2.  "php artisan test"

// 단위(Unit)테스트
// 프로그래머 관점에서 작성됩니다. 클래스의 특정 메소드 (또는 유닛)가 일련의 특정 태스크를 수행하도록 보장됩니다.

// 기능(Feature)테스트
// 사용자의 관점에서 작성됩니다. 그들은 사용자가 기대하는대로 시스템이 작동하는지 확인합니다.