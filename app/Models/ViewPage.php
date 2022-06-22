<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewPage extends Model
{
    use HasFactory;

    protected $fillable = ['post_id', 'user_uuid'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
