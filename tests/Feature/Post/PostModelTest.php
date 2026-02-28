<?php

namespace Tests\Feature\Post;

use App\Models\Post;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostModelTest extends TestCase
{
    use RefreshDatabase;



    public function test_it_can_create_a_post()
    {

        $user = User::factory()->create();

        $post = [
            'title' => 'Test Post',
            'content' => 'This is a test post',
            'user_id' => $user->id,
            'published_at' => now(),
            'status' => 'published',
            'slug' => 'test-post',
        ];

        $post = Post::create($post);

        $this->assertInstanceOf(Post::class, $post);
        $this->assertEquals('Test Post', $post->title);
        $this->assertEquals('This is a test post', $post->content);
        $this->assertEquals(1, $post->user_id);
        $this->assertEquals('published', $post->status);
        $this->assertEquals('test-post', $post->slug);
    }


    public function test_it_has_fillable_attributes()
    {
        $post = new Post();
        $fillable = ['title', 'content', 'user_id', 'published_at', 'status', 'slug'];

        $this->assertEquals($fillable, $post->getFillable());
    }
}
