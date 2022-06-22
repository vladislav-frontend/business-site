<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactsTranslation extends Model
{
    use HasFactory;

    protected $fillable = ['contacts_id', 'language_id', 'title', 'description'];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function contacts()
    {
        return $this->belongsTo(Contacts::class);
    }
}
