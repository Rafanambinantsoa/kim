<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function createFollow(User $user , Follow $follow){
        //verification that you can't follow yourself
        if(auth()->user()->id === $user->id){
            return back()->with('error' , "yo can't follow your self man");
        }        

        //you can't follow a user that you already following
        $checkExists = Follow::where([ [
            'user_id' , '=' , auth()->user()->id
        ], [
            'followeduser' , '=' , $user->id
        ] ])->count();

        // dd($checkExists);
        if($checkExists){
            return back()->with('warning','you can\'t follow a user that you already following');
        }

        $follow->user_id = auth()->user()->id;
        $follow->followeduser = $user->id;
        $follow->save();

        return back()->with('success' , "user successfully followed");
    }

    public function removeFollow(User $user){
        Follow::where([
            ['user_id' , '=' , auth()->user()->id],
            ['followeduser' , '=' , $user->id]
        ])->delete();

        return back()->with('success' , "user successfully unfollowed");

    }
}
