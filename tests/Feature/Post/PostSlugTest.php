<?php

namespace Tests\Feature\Post;

use Tests\TestCase;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostSlugTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_create_unique_slugs_for_duplicate_titles()
    {
        $post = Post::factory()->create(['title' => 'Test Post']);
        $post2 = Post::factory()->create(['title' => 'Test Post']);
        $post3 = Post::factory()->create(['title' => 'Test Post']);

        $this->assertNotEquals($post->slug, $post2->slug);
        $this->assertEquals('test-post-1', $post2->slug);
        $this->assertEquals('test-post-2', $post3->slug);
    }
}