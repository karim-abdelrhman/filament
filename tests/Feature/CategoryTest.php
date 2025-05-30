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
     * $response->assertOk() --> return 200
     * $response->assertAccepted() --> return 202
     * $response->assertNotFound() --> return 404
     * $response->assertBadRequest() --> return 400
     * $response->assertConflict() --> return 409
     * $response->status() --> return status code
     * $response->statusText() --> return status text
     */

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

    public function test_blade_return_successfully()
    {
        $response = $this->get('categories');
        $response->assertStatus(200);
    }

    public function test_blade_has_categories()
    {
        $categories = Category::factory()->count(5)->create();
        $response = $this->get('categories');
        $response->assertViewIs('categories.index');
        $response->assertViewHas('categories');
        foreach ($categories as $category) {
            $response->assertSee($category->name);
        }
    }

    public function test_store_category_failed_because_validation_fails()
    {
        $response = $this->post('categories' , [
            'name' => 'test category',
        ]);

        $response->assertStatus(302);

        $response->assertSessionHasErrors(['slug']);
    }
}
