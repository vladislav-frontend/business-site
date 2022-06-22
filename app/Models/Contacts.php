<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contacts extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;

    protected $fillable = ['phone_1', 'phone_2', 'telegram', 'email', 'skype'];

    public function translations()
    {
        return $this->hasMany(ContactsTranslation::class, 'contacts_id');
    }
}
