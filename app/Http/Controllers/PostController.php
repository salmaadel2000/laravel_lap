<?php

namespace App\Http\Controllers;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\PostStoreRequest;
use Illuminate\Support\Facades\File;
use App\Models\Comment;
use App\Models\User;


class PostController extends Controller
{
    function index(){
       $posts=Post::all();
       $posts=Post::simplePaginate(15);
       return view('posts.index',["posts" => $posts]);
    }

    function create(){
       return view("posts.create");
    }

    function store(PostStoreRequest $request)
    {
        $post = new Post;
        $post->title = $request->title;
        $post->body = $request->body;
        $post->posted_by = Auth::id();
    
        if ($request->hasFile('image')) {
            $originalFilename = $request->image->getClientOriginalName();
    
            $request->image->move(public_path('images'), $originalFilename);
    
            $post->image = $originalFilename;
        } else {
            $post->image = 'default.jpg';
        }
    
        $post->save();
    
        return redirect("/posts");
    }



    public function show($id)
    {
        $post = Post::findOrFail($id);
        $comments = Comment::where('post_id', $post->id)->get();
        return view('posts.show', compact('post', 'comments'));
    }

    function edit($id){
        $post=Post::find($id); 
        return view("posts.edit", ["post"=>$post]);
    }
   
public function update($id, PostStoreRequest $request)
{
    // Find the post by its ID
    $post = Post::find($id);
    
    // Check if the post exists
    if (!$post) {
        return redirect("/posts")->with('error', 'Post not found.');
    }
    
    // Check if the authenticated user is authorized to edit the post
    if ($post->posted_by != Auth::id()) {
        return redirect("/posts")->with('error', 'You are not authorized to edit this post.');
    }

    // Update the post with the new data
    $post->title = $request->title;
    $post->body = $request->body;

    // If the request contains a user_id parameter, update the posted_by field
    if ($request->has('user_id')) {
        $user = User::find($request->user_id);
        if (!$user) {
            return redirect("/posts")->with('error', 'User not found.');
        }
        $post->posted_by = $request->user_id;
    }

    // Save the changes to the database
    $post->save();

    // Redirect with a success message
    return redirect("/posts")->with('success', 'Post updated successfully.');
}

    function destroy(Request $request, $id){
        $post = Post::find($id);
        
        if (!$post) {
            return redirect("/posts")->with('error', 'Post not found.');
        }
        
        $imageName = $post->image;
        
        $imagePath = public_path('images') . '/' . $imageName;
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }

        $post->delete();
        
        return redirect("/posts")->with('success', 'Post deleted successfully.');
    }
    

// function destroy(Request $request, $id){
//     $post = Post::find($id);
    
//     if (!$post) {
//         return redirect("/posts")->with('error', 'Post not found.');
//     }
    
//     $imageName = $post->image;
//     $extension = pathinfo($imageName, PATHINFO_EXTENSION);
    
//     $newImage = time() . '-' . $request->name . '.' . $extension;
//     $imagePath = 'public/images/' . $imageName;
//     if ($post->image && $newImage === $post->image && Storage::exists($imagePath)) {
//         Storage::delete($imagePath);
    
//     $post->delete();
//     return redirect("/posts");
// }

    
// } 
}
