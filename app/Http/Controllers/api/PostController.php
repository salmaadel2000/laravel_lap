<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\PostStoreRequest; // Import PostStoreRequest if you have a form request for validation
use App\Models\User; // Import User model if not already imported

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        return PostResource::collection($posts);
    }
    public function show($id)
    {
        $post = Post::findOrFail($id);
        return new PostResource($post);
    }
    
    public function store(Request $request)
    {
        $post = new Post;
        $post->title = $request->title;
        $post->body = $request->body;
        $post->posted_by = $request->user_id;
        $post->save();

        return "stored";
    }

    public function update($id, Request $request)
    {
        // Find the post by its ID
        $post = Post::find($id);

        // Check if the post exists
        if (!$post) {
            return response()->json(['error' => 'Post not found.'], 404);
        }

        // Check if the authenticated user is authorized to edit the post
        if ($post->posted_by != $request->user_id) {
            return response()->json(['error' => 'You are not authorized to edit this post.'], 403);
        }

        // Update the post with the new data
        $post->title = $request->title;
        $post->body = $request->body;

        // Save the changes to the database
        $post->save();

        // Return a success message
        return response()->json(['message' => 'Post updated successfully.', 'post' => $post], 200);
    }

    public function destroy(Request $request, $id)
    {
        $post = Post::find($id);
    
        if (!$post) {
            return response()->json(['error' => 'Post not found.'], 404);
        }
    
        $post->delete();
    
        return response()->json(['message' => 'Post deleted successfully.']);
    }
    
}
