<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;

class PostController extends Controller
{
    public function list()
    {
        $posts = Post::withCount('likes')->get();

        return PostResource::collection($posts);
    }

    public function toggleReaction(Request $request)
    {
        $validatedData = $request->validate([
            'post_id' => 'required|integer|exists:posts,id',
            'like' => 'required|boolean',
        ]);

        $post = Post::find($validatedData['post_id']);
        if (!$post) {
            return response()->json([
                'status' => 404,
                'message' => 'Model not found',
            ]);
        }

        if ($post->user_id == auth()->id()) {
            return response()->json([
                'status' => 500,
                'message' => 'You cannot like your own post',
            ]);
        }

        $like = Like::where('post_id', $validatedData['post_id'])
            ->where('user_id', auth()->id())
            ->first();

        if ($like && $validatedData['like']) {
            return response()->json([
                'status' => 500,
                'message' => 'You have already liked this post',
            ]);
        } elseif ($like && !$validatedData['like']) {
            $like->delete();

            return response()->json([
                'status' => 200,
                'message' => 'You have unliked this post successfully',
            ]);
        } elseif (!$like && $validatedData['like']) {
            Like::create([
                'post_id' => $validatedData['post_id'],
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'You have liked this post successfully',
            ]);
        }
    }
}
