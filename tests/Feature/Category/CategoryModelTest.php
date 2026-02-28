<?php

namespace Tests\Feature\Category;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_it_can_create_a_category()
    {
        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'This is a test category',
        ]);

        $this->assertInstanceOf(Category::class, $category);
        $this->assertEquals('Test Category', $category->name);
        $this->assertEquals('test-category', $category->slug);
        $this->assertEquals('This is a test category', $category->description);
    }

    public function test_it_can_have_multiple_posts()
    {
        $category = Category::factory()->create();
        $posts = Post::factory()->count(3)->create();

        $category->posts()->attach($posts->pluck('id'));

        $this->assertCount(3, $category->posts);
    }
}
