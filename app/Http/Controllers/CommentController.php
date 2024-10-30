<?php

namespace App\Http\Controllers;

use App\Events\NewCommentEvent;
use App\Models\Comment;
use App\Models\Post;
use App\Notifications\NewCommentNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class CommentController extends Controller
{

    /**
     * It handles a new comment of a post Id.
     * It validates the input parameters
     * Assign the post ID from the route parameter
     * If parent_id is provided, check that it belongs to the same post
     * Creates the comment in the Database
     * creates NewCommentNotification to send email
     * @param Request $request
     * @param post $post
     * @return JsonResponse
    */
    public function store(Request $request, Post $post): JsonResponse
    {

        $data = $request->validate([
            'text' => 'required|string',
            'author' => 'required|string',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        // Assign the post ID from the route parameter
        $data['post_id'] = $post->id;

        // If parent_id is provided, check that it belongs to the same post
        if ($data['parent_id']) {
            $parentComment = Comment::find($data['parent_id']);

            // Check if the parent comment belongs to the specified post
            if ($parentComment->post_id !== $post->id) {
                return response()->json(['error' => 'The parent comment does not belong to the specified post'], 400);
            }
        }

        $comment = Comment::create($data);

        // Notify the administrator
        Notification::route('mail', env('MAIL_ADMIN'))
                    ->notify(new NewCommentNotification($comment));

        return response()->json($comment, 201);
    }


    /**
     * It displays the Comments of a given PostId
     * @param int $postId
     * @return JsonResponse
    */
    public function index(int $postId): JsonResponse
    {
        $comments = Comment::where('post_id', $postId)
            ->whereNull('parent_id')
            ->with('replies')
            ->get();

        return response()->json($comments);
    }

    /**
     * It updates a given CommentId
     * @param int $id
     * @return JsonResponse
    */
    public function update(Request $request, int $id)
    {
        $comment = Comment::findOrFail($id);
        $comment->update($request->only('text'));

        return response()->json($comment);
    }

    /**
     * It deletes a given CommentId
     * @param int $id
     * @return JsonResponse
    */
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return response()->json(null, 204);
    }

    /**
     * It show a given CommentId
     * @param int $id
     * @return JsonResponse
    */
    public function show($id)
    {
        $comment = Comment::findOrFail($id);
       return response()->json($comment, 200);
    }
}
