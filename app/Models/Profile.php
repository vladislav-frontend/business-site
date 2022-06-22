<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    const TABLE = 'profiles';

    protected $table = self::TABLE;

    protected $fillable = ['user_id', 'image', 'position', 'contacts', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setImageAttribute($value)
    {
        $attribute_name = "image";
        $disk = config('backpack.base.root_disk_name');
        //$destination_path = "storage/images/$next_post_id";

        if ($value==null) {
            \Storage::disk($disk)->delete($this->{$attribute_name});

            $this->attributes[$attribute_name] = null;
        }
    }
}
