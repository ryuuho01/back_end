<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;
    protected $guarded = array('id');

    public static $rules = array(
        'area_id' => 'required',
        'genre_id' => 'required',
        'shop_name' => 'required',
        'description' => 'required|max:120',
    );

    public function area()
    {
        return $this->belongsTo('App\Models\Area');
    }

    public function genre()
    {
        return $this->belongsTo('App\Models\Genre');
    }

    public function getFavorites()
    {
        return $this->hasMany('App\Models\Favorite');
    }

    public function getReservations()
    {
        return $this->hasMany('App\Models\Reservation');
    }

    public function getEvaluations()
    {
        return $this->hasMany('App\Models\Evaluation');
    }
}