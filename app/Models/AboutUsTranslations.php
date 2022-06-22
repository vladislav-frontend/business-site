<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUsTranslations extends Model
{
    use HasFactory;

    protected $fillable = ['language_id', 'aboutus_id', 'title', 'description', 'summary'];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function aboutus()
    {
        return $this->belongsTo(AboutUs::class);
    }
}
