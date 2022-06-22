<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeTranslation extends Model
{
    use HasFactory;

    protected $fillable = ['home_id', 'language_id', 'title', 'description', 'summary'];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function aboutus()
    {
        return $this->belongsTo(Home::class);
    }
}
