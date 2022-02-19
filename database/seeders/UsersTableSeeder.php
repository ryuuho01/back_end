<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'name' => 'manager',
            'email' => 'test0@test.com',
            'password' => Hash::make('123456'),
            'verify_email' => 1,
            'authority' => 0
        ];
        DB::table('users')->insert($param);
        $param = [
            'shop_id' => 1,
            'name' => '仙人manager',
            'email' => 'test1@test.com',
            'password' => Hash::make('123456'),
            'verify_email' => 1,
            'authority' => 1
        ];
        DB::table('users')->insert($param);
        $param = [
            'shop_id' => 2,
            'name' => '牛助manager',
            'email' => 'test2@test.com',
            'password' => Hash::make('123456'),
            'verify_email' => 1,
            'authority' => 1
        ];
        DB::table('users')->insert($param);
        $param = [
            'shop_id' => 3,
            'name' => '戦慄manager',
            'email' => 'test3@test.com',
            'password' => Hash::make('123456'),
            'verify_email' => 1,
            'authority' => 1
        ];
        DB::table('users')->insert($param);
        $param = [
            'shop_id' => 4,
            'name' => 'ルークmanager',
            'email' => 'test4@test.com',
            'password' => Hash::make('123456'),
            'verify_email' => 1,
            'authority' => 1
        ];
        DB::table('users')->insert($param);
        $param = [
            'shop_id' => 5,
            'name' => '志摩屋manager',
            'email' => 'test5@test.com',
            'password' => Hash::make('123456'),
            'verify_email' => 1,
            'authority' => 1
        ];
        DB::table('users')->insert($param);
        $param = [
            'shop_id' => 6,
            'name' => '香manager',
            'email' => 'test6@test.com',
            'password' => Hash::make('123456'),
            'verify_email' => 1,
            'authority' => 1
        ];
        DB::table('users')->insert($param);
        $param = [
            'shop_id' => 7,
            'name' => 'JJmanager',
            'email' => 'test7@test.com',
            'password' => Hash::make('123456'),
            'verify_email' => 1,
            'authority' => 1
        ];
        DB::table('users')->insert($param);
        $param = [
            'shop_id' => 8,
            'name' => 'らーめん極みmanager',
            'email' => 'test8@test.com',
            'password' => Hash::make('123456'),
            'verify_email' => 1,
            'authority' => 1
        ];
        DB::table('users')->insert($param);
        $param = [
            'shop_id' => 9,
            'name' => '鳥雨manager',
            'email' => 'test9@test.com',
            'password' => Hash::make('123456'),
            'verify_email' => 1,
            'authority' => 1
        ];
        DB::table('users')->insert($param);
        $param = [
            'shop_id' => 10,
            'name' => '築地色合manager',
            'email' => 'test10@test.com',
            'password' => Hash::make('123456'),
            'verify_email' => 1,
            'authority' => 1
        ];
        DB::table('users')->insert($param);
        $param = [
            'shop_id' => 11,
            'name' => '晴海manager',
            'email' => 'test11@test.com',
            'password' => Hash::make('123456'),
            'verify_email' => 1,
            'authority' => 1
        ];
        DB::table('users')->insert($param);
        $param = [
            'shop_id' => 12,
            'name' => '三子manager',
            'email' => 'test12@test.com',
            'password' => Hash::make('123456'),
            'verify_email' => 1,
            'authority' => 1
        ];
        DB::table('users')->insert($param);
        $param = [
            'shop_id' => 13,
            'name' => '八戒manager',
            'email' => 'test13@test.com',
            'password' => Hash::make('123456'),
            'verify_email' => 1,
            'authority' => 1
        ];
        DB::table('users')->insert($param);
        $param = [
            'shop_id' => 14,
            'name' => '福助manager',
            'email' => 'test14@test.com',
            'password' => Hash::make('123456'),
            'verify_email' => 1,
            'authority' => 1
        ];
        DB::table('users')->insert($param);
        $param = [
            'shop_id' => 15,
            'name' => 'ラー北manager',
            'email' => 'test15@test.com',
            'password' => Hash::make('123456'),
            'verify_email' => 1,
            'authority' => 1
        ];
        DB::table('users')->insert($param);
        $param = [
            'shop_id' => 16,
            'name' => '翔manager',
            'email' => 'test16@test.com',
            'password' => Hash::make('123456'),
            'verify_email' => 1,
            'authority' => 1
        ];
        DB::table('users')->insert($param);
        $param = [
            'shop_id' => 17,
            'name' => '経緯manager',
            'email' => 'test17@test.com',
            'password' => Hash::make('123456'),
            'verify_email' => 1,
            'authority' => 1
        ];
        DB::table('users')->insert($param);
        $param = [
            'shop_id' => 18,
            'name' => '漆manager',
            'email' => 'test18@test.com',
            'password' => Hash::make('123456'),
            'verify_email' => 1,
            'authority' => 1
        ];
        DB::table('users')->insert($param);
        $param = [
            'shop_id' => 19,
            'name' => 'THE TOOLmanager',
            'email' => 'test19@test.com',
            'password' => Hash::make('123456'),
            'verify_email' => 1,
            'authority' => 1
        ];
        DB::table('users')->insert($param);
        $param = [
            'shop_id' => 20,
            'name' => '木船manager',
            'email' => 'test20@test.com',
            'password' => Hash::make('123456'),
            'verify_email' => 1,
            'authority' => 1
        ];
        DB::table('users')->insert($param);
        $param = [
            'shop_id' => null,
            'name' => 'テストユーザー',
            'email' => 'test21@test.com',
            'password' => Hash::make('123456'),
            'verify_email' => 1,
            'authority' => 2
        ];
        DB::table('users')->insert($param);
    }
}
