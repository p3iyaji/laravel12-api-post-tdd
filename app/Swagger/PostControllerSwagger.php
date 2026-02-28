<?php

namespace App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Posts API Documentation",
 *     version="1.0.0",
 *     description="API documentation for Posts management system",
 *     @OA\Contact(
 *         email="admin@example.com"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="http://localhost:8000/api",
 *     description="Local API Server"
 * )
 * 
 * @OA\Tag(
 *     name="Posts",
 *     description="API Endpoints for Posts"
 * )
 * 
 * @OA\Schema(
 *     schema="Post",
 *     type="object",
 *     title="Post",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="My First Post"),
 *     @OA\Property(property="content", type="string", example="This is the content of my first post"),
 *     @OA\Property(property="slug", type="string", example="my-first-post"),
 *     @OA\Property(property="status", type="string", enum={"published", "draft"}, example="published"),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="published_at", type="string", format="date-time", nullable=true, example="2024-01-01T12:00:00Z"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-01T12:00:00Z")
 * )
 * 
 * @OA\Schema(
 *     schema="PostRequest",
 *     type="object",
 *     title="Post Request",
 *     required={"title", "content", "user_id", "status", "slug"},
 *     @OA\Property(property="title", type="string", maxLength=255, example="My New Post"),
 *     @OA\Property(property="content", type="string", example="This is the content of my new post"),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="status", type="string", enum={"published", "draft"}, example="published"),
 *     @OA\Property(property="slug", type="string", maxLength=255, example="my-new-post"),
 *     @OA\Property(property="published_at", type="string", format="date-time", nullable=true, example="2024-01-01T12:00:00Z")
 * )
 * 
 * @OA\Schema(
 *     schema="PostUpdateRequest",
 *     type="object",
 *     title="Post Update Request",
 *     @OA\Property(property="title", type="string", maxLength=255, example="My Updated Post"),
 *     @OA\Property(property="content", type="string", example="This is the updated content"),
 *     @OA\Property(property="status", type="string", enum={"published", "draft"}, example="draft"),
 *     @OA\Property(property="slug", type="string", maxLength=255, example="my-updated-post"),
 *     @OA\Property(property="published_at", type="string", format="date-time", nullable=true, example="2024-01-01T12:00:00Z")
 * )
 * 
 * @OA\Schema(
 *     schema="PostCollection",
 *     type="object",
 *     title="Post Collection",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Post")
 *     ),
 *     @OA\Property(
 *         property="links",
 *         type="object",
 *         @OA\Property(property="first", type="string", example="http://localhost:8000/api/posts?page=1"),
 *         @OA\Property(property="last", type="string", example="http://localhost:8000/api/posts?page=10"),
 *         @OA\Property(property="prev", type="string", nullable=true),
 *         @OA\Property(property="next", type="string", nullable=true)
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         @OA\Property(property="current_page", type="integer", example=1),
 *         @OA\Property(property="from", type="integer", example=1),
 *         @OA\Property(property="last_page", type="integer", example=10),
 *         @OA\Property(property="path", type="string", example="http://localhost:8000/api/posts"),
 *         @OA\Property(property="per_page", type="integer", example=10),
 *         @OA\Property(property="to", type="integer", example=10),
 *         @OA\Property(property="total", type="integer", example=100)
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="ErrorResponse",
 *     type="object",
 *     @OA\Property(property="message", type="string", example="Post not found"),
 *     @OA\Property(
 *         property="errors",
 *         type="object",
 *         @OA\Property(
 *             property="field_name",
 *             type="array",
 *             @OA\Items(type="string", example="The field name is required.")
 *         )
 *     )
 * )
 */
class PostControllerSwagger
{
    /**
     * @OA\Get(
     *     path="/posts",
     *     summary="List all posts",
     *     tags={"Posts"},
     *     operationId="index",
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search in title and content",
     *         required=false,
     *         @OA\Schema(type="string", example="laravel")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by status",
     *         required=false,
     *         @OA\Schema(type="string", enum={"published", "draft"}, example="published")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/PostCollection")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function index() {}

    /**
     * @OA\Post(
     *     path="/posts",
     *     summary="Create a new post",
     *     tags={"Posts"},
     *     operationId="store",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Post data",
     *         @OA\JsonContent(ref="#/components/schemas/PostRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Post created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", ref="#/components/schemas/Post")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function store() {}

    /**
     * @OA\Get(
     *     path="/posts/{slug}",
     *     summary="Get a specific post by slug",
     *     tags={"Posts"},
     *     operationId="show",
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         description="Post slug",
     *         required=true,
     *         @OA\Schema(type="string", example="my-first-post")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", ref="#/components/schemas/Post")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Post not found")
     *         )
     *     )
     * )
     */
    public function show() {}

    /**
     * @OA\Put(
     *     path="/posts/{slug}",
     *     summary="Update an existing post",
     *     tags={"Posts"},
     *     operationId="update",
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         description="Post slug",
     *         required=true,
     *         @OA\Schema(type="string", example="my-first-post")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Post data to update",
     *         @OA\JsonContent(ref="#/components/schemas/PostUpdateRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", ref="#/components/schemas/Post")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Post not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function update() {}

    /**
     * @OA\Delete(
     *     path="/posts/{slug}",
     *     summary="Delete a post",
     *     tags={"Posts"},
     *     operationId="destroy",
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         description="Post slug",
     *         required=true,
     *         @OA\Schema(type="string", example="my-first-post")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Post deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Post deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Post not found")
     *         )
     *     )
     * )
     */
    public function destroy() {}
}
