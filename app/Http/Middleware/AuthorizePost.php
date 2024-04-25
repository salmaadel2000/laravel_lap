<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Post;
use Illuminate\Http\Request;

class AuthorizePost
{
    public function handle(Request $request, Closure $next)
    {
        $postId = $request->route('id');
        $post = Post::find($postId);

        if (!$post || $post->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized.'], 403);
        }

        return $next($request);
    }
}
