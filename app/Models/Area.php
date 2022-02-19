<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;
    protected $guarded = array('id');

    public static $rules = array(
        'area_name' => 'required',
    );

    // public function getareasData()
    // {
    //     // return $this->area->area_name;
    //     return $this->area_name;
    // }
}

    