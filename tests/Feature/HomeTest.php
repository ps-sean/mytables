<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeTest extends TestCase
{
    public function test_can_see_home_page()
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
    }
}
