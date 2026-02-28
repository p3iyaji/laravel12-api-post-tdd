<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::latest();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%$search%")
                ->orWhere('content', 'like', "%$search%");
        }
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        $posts = $query->paginate(10)->withQueryString();

        return response()->json(new PostCollection($posts), 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'published_at' => 'sometimes|date',
            'status' => 'required|in:published,draft',
            'slug' => 'required|string|max:255|unique:posts,slug',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $post = Post::create([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'user_id' => $request->input('user_id'),
            'published_at' => $request->input('published_at'),
            'status' => $request->input('status'),

        ]);

        return (new PostResource($post))->response()->setStatusCode(201);
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)->first();
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }
        return (new PostResource($post))->response()->setStatusCode(200);
    }

    public function update(Request $request, $slug)
    {
        $post = Post::where('slug', $slug)->first();

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            'published_at' => 'sometimes|date',
            'status' => 'sometimes|in:published,draft',
            'slug' => 'sometimes|string|max:255|unique:posts,slug,' . $post->id,
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $post->update($validator->validated());

        return (new PostResource($post))->response()->setStatusCode(200);
    }

    public function destroy($slug)
    {
        $post = Post::where('slug', $slug)->first();
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }
        $post->delete();
        return response()->json(['message' => 'Post deleted successfully'], 204);
    }
}
