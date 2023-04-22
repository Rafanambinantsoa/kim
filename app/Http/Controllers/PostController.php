<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function actuallyUpdate(Post $post,Request $request){
        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);

        $post->update($incomingFields);

        return redirect()->back()->with('success', "post updated successfully");
    }

    public function showUpdateForm(Post $post){
        return view('edit-post' , ['post' => $post]);
    }

    public function delete(Post $post){
        // if(auth()->user()->cannot('delete' , $post)){
        //     return "you can't do that  man";
        // }
        $post->delete();
        return redirect('/profile/'.auth()->user()->username)->with('success' , "Congrats  , you successfully deleted the posts");
    }

    public function viewSinglePost(Post $post){
        // dd($post);
        //Mampisa anle markdown
        $post['body'] = Str::markdown($post['body']);
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
        $post = Post::create($data);

        return redirect("/post/{$post->id}")->with('success','Your post has been created successfully.');
    
    }

    public function showCreateForm(){
        return view('create-post');
    }
}
