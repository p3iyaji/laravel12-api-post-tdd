<?php

namespace Tests\Feature\Post;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class PostExcerptTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_generate_post_excerpt()
    {
        $post = Post::factory()->create([
            'content' => 'This is a very long content that should 
            be truncatied too create an excerpt. ' . str_repeat('More text. ', 50)
        ]);

        $this->assertStringEndsWith('...', $post->excerpt);
        $this->assertLessThanOrEqual(200, strlen($post->excerpt));
    }
}
