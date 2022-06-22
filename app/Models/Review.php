<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;

    protected $fillable = ['post_id', 'user_ip', 'user_uuid', 'user_name', 'description', 'rating', 'status', 'user_id', 'admin_comment'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
