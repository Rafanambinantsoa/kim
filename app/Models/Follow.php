<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;

    //a function who return the user who is doing the following
    public function userDoingFollowing(){
        return $this->belongsTo(User::class , 'user_id');
    }
    // a function who return the user who is being followed
    public function userBeingFollowed(){
        return $this->belongsTo(User::class , 'followeduser');
    }
}
