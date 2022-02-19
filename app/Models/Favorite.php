<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;
    protected $guarded = array('id');

    public static $rules = array(
        'user_id' => 'required',
        'shop_id' => 'required',
        'favorite' => 'required'
    );

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
