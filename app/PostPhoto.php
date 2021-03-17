<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostPhoto extends Model
{
    public function post()
    {
        return $this->belongsTo('App\Post', 'posts_id');
    }
}
