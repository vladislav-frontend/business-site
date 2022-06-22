<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VacationTranslations extends Model
{
    use HasFactory;

    protected $fillable = ['language_id', 'vacation_id', 'name', 'summary'];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function vacation()
    {
        return $this->belongsTo(AboutUs::class);
    }
}
