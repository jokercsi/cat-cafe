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

        $response2 = $this->get('/contact');
        $response2 ->assertStatus(200);


        # redirect to login page (if not loged in)
        $response3 = $this->get('/admin/blogs');
        $response3 ->assertStatus(302);  
    }

    public function test_interacting_with_the_session()
    {
        $response = $this->withSession(['banned' => false])->get('/');
    }

}
