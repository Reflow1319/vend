<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $user = factory(User::class)->make(['role' => 'admin']);
        $response = $this->actingAs($user)
            ->get('/');

        $response->assertStatus(200);
    }
}
