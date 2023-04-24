<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;
    use Searchable;


    protected $fillable = [
        'title',
        'body',
        'user_id'
    ];
    //Seule les data dans cette seront rechercher par le mot clé
    public function toSearchableArray()
    {
        return [
            'title' => $this->title,
            'body' => $this->body
        ];
        
    }
//fakana anle donner anaty cle etrangere (specialiser ho anle image ou bien avatar)
    public function cletrangere(){
        return $this->belongsTo(User::class,'user_id');
    }
}
