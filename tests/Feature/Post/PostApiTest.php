<?php

use App\Models\User;
use App\Models\Post;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;

class PostApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_list_all_posts()
    {
        Post::factory()->count(5)->published()->create();
        Post::factory()->count(5)->draft()->create();

        $response = $this->getJson('/api/posts');

        $response->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJson(function (AssertableJson $json) {
                $json->has('data.0', function ($json) {
                    $json->hasAll([
                        'id',
                        'title',
                        'content',
                        'published_at',
                        'status',
                        'slug',
                        'created_at',
                        'updated_at',
                        'user_id',
                    ])
                        ->whereAllType([
                            'id' => 'integer',
                            'title' => 'string',
                            'content' => 'string',
                            'status' => 'string',
                            'slug' => 'string',
                            'created_at' => 'string',
                            'updated_at' => 'string',
                            'user_id' => 'integer',
                        ])
                        ->where(
                            'published_at',
                            fn($value) =>
                            is_null($value) || is_string($value)
                        );
                })
                    ->has('meta')
                    ->has('links');
            });
    }

    public function test_it_can_create_a_post()
    {

        $postData = [

            'title' => 'Test Post',
            'content' => 'This is a test post',
            'user_id' => User::factory()->create()->id,
            'published_at' => now(),
            'status' => 'published',
            'slug' => 'test-post',
        ];

        $response = $this->postJson('/api/posts', $postData);

        $response->assertStatus(201)
            ->assertJson(function (AssertableJson $json) use ($postData) {
                $json->has('data.id')
                    ->where('data.title', $postData['title'])
                    ->where('data.content', $postData['content'])
                    ->where('data.user_id', $postData['user_id'])
                    ->where(
                        'data.published_at',
                        $postData['published_at']?->format('Y-m-d H:i:s')
                    )
                    ->where('data.status', $postData['status'])
                    ->where('data.slug', $postData['slug'])
                    ->etc();
            });

        $this->assertDatabaseHas('posts', [
            'title' => $postData['title'],
            'content' => $postData['content'],
            'user_id' => $postData['user_id'],
            'published_at' => $postData['published_at']?->format('Y-m-d H:i:s'),
            'status' => $postData['status'],
            'slug' => $postData['slug'],
        ]);
    }

    public function test_it_validates_required_fields()
    {
        $response = $this->postJson('/api/posts', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'content', 'user_id', 'status', 'slug']);
    }

    public function test_it_can_show_a_single_post()
    {
        $post = Post::factory()->published()->create();

        $response = $this->getJson("/api/posts/{$post->slug}");

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) use ($post) {
                $json->has('data')
                    ->where('data.id', $post->id)
                    ->where('data.title', $post->title)
                    ->where('data.content', $post->content)
                    ->where('data.user_id', $post->user_id)
                    ->where('data.published_at', $post->published_at?->format('Y-m-d H:i:s'))
                    ->where('data.status', $post->status)
                    ->where('data.slug', $post->slug)
                    ->etc();
            });
    }

    public function test_it_returns_404_for_non_existent_post()
    {
        $response = $this->getJson('/api/posts/non-existent-slug');

        $response->assertStatus(404);
    }

    public function test_it_can_update_a_post()
    {
        $post = Post::factory()->published()->create();

        $updateData = [
            'title' => 'Updated Title',
            'content' => 'Updated content',
            'published_at' => now(),
            'status' => 'published',
            'slug' => 'updated-title',
        ];

        $response = $this->putJson("/api/posts/{$post->slug}", $updateData);

        // API response assertions
        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) use ($updateData) {
                $json->has('data')
                    ->where('data.title', $updateData['title'])
                    ->where('data.content', $updateData['content'])
                    ->where('data.published_at', $updateData['published_at']->format('Y-m-d H:i:s'))
                    ->where('data.status', $updateData['status'])
                    ->where('data.slug', $updateData['slug']);
            });

        // Database assertions
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => $updateData['title'],
            'content' => $updateData['content'],
            'published_at' => $updateData['published_at']->format('Y-m-d H:i:s'),
            'status' => $updateData['status'],
            'slug' => $updateData['slug'],
        ]);
    }

    public function test_it_can_delete_a_post()
    {
        $post = Post::factory()->published()->create();

        $response = $this->deleteJson("/api/posts/{$post->slug}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
        ]);
    }

    public function test_it_can_filter_posts_by_status()
    {
        Post::factory()->count(5)->published()->create();
        Post::factory()->count(5)->draft()->create();

        $response = $this->getJson('/api/posts?status=published');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }

    public function test_it_can_search_posts()
    {
        Post::factory()->published()->create(['title' => 'Test Post']);
        Post::factory()->published()->create(['title' => 'Another Test Post']);
        Post::factory()->published()->create(['title' => 'Test Post with Keyword']);

        $response = $this->getJson('/api/posts?search=Another');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Another Test Post');
    }
}
