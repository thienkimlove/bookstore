<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Crawler extends Model
{
    protected $fillable = ['link', 'page', 'keyword', 'post_id'];

    public function post()
    {
        return $this->hasOne(Post::class);
    }
}
