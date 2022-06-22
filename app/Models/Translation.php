<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;

    protected $fillable = ['language_id', 'post_id', 'name', 'summary', 'content', 'title', 'description'];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
