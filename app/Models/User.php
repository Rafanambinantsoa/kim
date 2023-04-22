<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
    ];

//Une fonction qui retourne le liend l'image dynamiquement 
    protected function avatar():Attribute{
        return Attribute::make(get:function($value){
            return $value? '/storage/avatars/'.$value.'.jpg' : '/fallback-avatar.jpg';
        });
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //a function who return all the post of the user who follows this user
    public function postFeed(){
        return $this->hasManyThrough(Post::class,Follow::class,'user_id','user_id','id','followeduser');
    }

    //Fonction pour recuprer tous les followers de notre users
    public function followers(){
        return $this->hasMany(Follow::class,'followeduser');
    }

    //a function to return all the user who follows this user
    public function followingsThisUser(){
        return $this->hasMany(Follow::class,'user_id');
    }

    //cle etrangère fi recuperena anle tous les blogs ce la personne en question a fais 
    public function posts(){
        return $this->hasMany(Post::class,'user_id');
    }
}
