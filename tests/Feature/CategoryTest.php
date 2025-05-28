<?php

namespace Tests\Feature;

use App\Models\Category;
use Database\Factories\CategoryFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('config:clear');
    }

    /**
     * A basic feature test example.
     */
    public function test_index_method_return_list_of_categories(): void
    {
        $response = $this->get('/api/categories');

        $categories = $response->json();

        foreach ($categories as $category) {
            $this->assertDatabaseHas('categories', $category);
        }
    }

    public function test_can_create_categories_using_factory()
    {
        $categories = CategoryFactory::times(5)->create();
        $this->assertCount(5, $categories);
    }

    public function test_index_method_return_success()
    {
        $response = $this->get('/api/categories');
        $response->assertStatus(200);
    }

    public function test_can_create_category_using_model()
    {
        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
        ]);
        $this->assertDatabaseHas('categories', $category->toArray());
    }

    public function test_send_request_to_store_method_and_create_category_successfully()
    {
        $category = [
                'name' => 'Test Category1',
                'slug' => 'test-category1',
        ];


        $response = $this->postJson('/api/categories' , $category);

        $response->assertStatus(201);

        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category1',
            'slug' => 'test-category1',
        ]);

        $response->assertJson($category);
    }

    public function test_can_delete_a_category()
    {
        $category = Category::factory()->create();
        $response = $this->deleteJson('/api/categories/' . $category->id);
        $response->assertStatus(204);
        $this->assertDatabaseMissing('categories', $category->toArray());
    }

    public function test_can_update_category()
    {
        // create category
        $category = Category::create([
            'name' => 'karim',
            'slug' => 'karim'
        ]);

        $updatedCategory = [
            'name' => 'karim updated',
            'slug' => 'karim-slug-update'
        ];

        // send a request to update method to update the record
        $response = $this->putJson('/api/categories/' . $category->id , $updatedCategory);

        // assert the updated success status
        $response->assertStatus(200);

        // assert database has the updated record
        $this->assertDatabaseHas('categories', $updatedCategory);

    }
}
