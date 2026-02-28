<?php

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostAdvancedFeaturesTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_automatically_generates_unique_slugs()
    {
        $post1 = Post::factory()->create(['title' => 'Test Post']);
        $post2 = Post::factory()->create(['title' => 'Test Post']);

        $this->assertNotEquals($post1->slug, $post2->slug);
        $this->assertEquals('test-post', $post1->slug);
        $this->assertEquals('test-post-1', $post2->slug);
    }

    public function test_it_can_get_published_posts_only()
    {
        Post::factory()->count(5)->published()->create();
        Post::factory()->count(5)->draft()->create();

        $publishedPosts = Post::published()->get();
        $this->assertCount(5, $publishedPosts);
        $this->assertEquals('published', $publishedPosts->first()->status);
    }

    public function test_it_can_order_posts_by_published_date()
    {
        $oldPost = Post::factory()->published()->create(['published_at' => now()->subDays(5)]);
        $newPost = Post::factory()->published()->create(['published_at' => now()->subDays(1)]);

        $posts = Post::published()->latest('published_at')->get();
        $this->assertTrue($posts->first()->is($newPost));
        $this->assertTrue($posts->last()->is($oldPost));
    }
}
