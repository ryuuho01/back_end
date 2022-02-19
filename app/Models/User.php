<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

// class User extends Model
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    // protected $guard = 'client';
    //$fillableに指定したカラムのみ、create()やfill(),update()で値が代入される
    //guardedはその逆で、↑で値が代入されなくなる
    //protectedはそのクラス自身と継承クラスからアクセス可能(非公開だけど継承は可能)
    //privateは同じクラスの中でのみアクセスが可能。非公開で継承クラスからもアクセス不可。
    //継承は、”class ○○○ extends 継承するclass名”で継承する。
    // protected $guard = 'api';
    protected $fillable = [
        'name',
        'email',
        'password',
        // 'authority',
        // 'verify_email',
    ];

    //$hiddenはデータ取得される時に取得しないフィールドとして指定するもの
    protected $hidden = [
        // 'shop_id',
        // 'authority',
        'password',
        // 'remember_token',
        'verify_email',
        'verify_token',
        'verify_date',
        'verify_email_address',
        // 'deleted_at'
    ];

    public function shop()
    {
        return $this->belongsTo('App\Models\Shop');
    }

    //$castsはDBから取得したデータを自動型変換する変数。便利。
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    //どこからでもアクセス可能。アクセス修飾子がない場合はpublicと同じ。
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }


    public function getJWTCustomClaims()
    {
        return [];
    }
}
