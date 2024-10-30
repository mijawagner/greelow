<?php
namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * List all the posts
     * @return JsonResponse
    */
    public function index()
    {
        $posts = Post::all();
        return response()->json($posts, 200);
    }

    /**
     * It handles a new Post in the Database
     * @param Request $request
     * @return JsonResponse
    */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'user_id' => 'required|exists:users,id', // assuming a user_id is needed to identify the author
        ]);

        $post = Post::create($data);

        return response()->json($post, 201);
    }

    /**
     * Show a specific post
     * @param Request $request
     * @return JsonResponse
    */
    public function show(Post $post)
    {
        return response()->json($post, 200);
    }

    /**
     * Updates a specific post
     * @param Request $request
     * @return JsonResponse
    */
    public function update(Request $request, Post $post): JsonResponse
    {
        $data = $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
        ]);

        $post->update($data);
        return response()->json($post, 200);
    }

    /**
     * Deletes a specific post
     * @param Request $request
     * @return JsonResponse
    */
    public function destroy($id): JsonResponse
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return response()->json(null, 204);
    }
}
