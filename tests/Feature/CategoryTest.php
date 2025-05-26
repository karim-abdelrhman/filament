<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_index_method_return_list_of_categories(): void
    {
        $response = $this->get('/api/categories');

        $response->assertStatus(200);

        $data = $response->json();

        $categories = Category::all();

        $this->assertEquals($categories->toArray(), $data);

        $this->assertCount($categories->count(), $data);
    }
}
