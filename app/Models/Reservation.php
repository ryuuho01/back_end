<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;
    protected $guarded = array('id');

    public static $rules = array(
        'user_id' => 'required',
        'shop_id' => 'required',
        'reservation_date' => 'required',
        'num_members' => 'required'
    );

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function shop()
    {
        return $this->belongsTo('App\Models\Shop');
    }
}
