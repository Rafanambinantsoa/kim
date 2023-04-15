<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function viewSinglePost(Post $post){
        // dd($post);
        return view('single-post',['post' => $post]);
    }

    public function storeNewPost(Request $request){
        $data = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);

        $data['title'] = strip_tags($data['title']);
        $data['body'] = strip_tags($data['body']);
        $data['user_id'] = auth()->id();

        //Insertion dans la BD
        Post::create($data);

        return 'Post created successfully';
    
    }

    public function showCreateForm(){
        return view('create-post');
    }
}
