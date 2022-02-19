<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EvaluationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'user_id' => 2,
            'shop_id' => 1,
            'evaluation' => 5,
            'comment' => "大変美味しかったです。店員のサービスも良かったです。",
            'answer' => 1,
            'updated_at' => "2017-01-30 18:27:31"
        ];
        DB::table('evaluations')->insert($param);
        $param = [
            'user_id' => 3,
            'shop_id' => 1,
            'evaluation' => 2,
            'comment' => "まぁまぁでした。",
            'answer' => 1,
            'updated_at' => "2018-06-30 11:25:31"
        ];
        DB::table('evaluations')->insert($param);
        $param = [
            'user_id' => 4,
            'shop_id' => 1,
            'evaluation' => 3,
            'comment' => "また来たいです。",
            'answer' => 1,
            'updated_at' => "2021-01-04 15:27:30"
        ];
        DB::table('evaluations')->insert($param);
        $param = [
            'user_id' => 5,
            'shop_id' => 1,
            'evaluation' => 4,
            'comment' => "もう少しネタの種類がたくさんあると良いと感じました。また家族で来ようと思います。",
            'answer' => 1,
            'updated_at' => "2020-01-22 11:07:20"
        ];
        DB::table('evaluations')->insert($param);
        $param = [
            'user_id' => 6,
            'shop_id' => 1,
            'evaluation' => 1,
            'comment' => null,
            'answer' => 1,
            'updated_at' => "2018-07-20 11:17:50"
        ];
        DB::table('evaluations')->insert($param);
        $param = [
            'user_id' => 7,
            'shop_id' => 1,
            'evaluation' => 1,
            'comment' => "厳しめですが、もう少し店員の対応が良ければと思いました。",
            'answer' => 1,
            'updated_at' => "2021-02-14 14:00:18"
        ];
        DB::table('evaluations')->insert($param);
        $param = [
            'user_id' => 8,
            'shop_id' => 1,
            'evaluation' => 4,
            'comment' => "また来ます。",
            'answer' => 1,
            'updated_at' => "2016-09-19 14:09:11"
        ];
        DB::table('evaluations')->insert($param);
        $param = [
            'user_id' => 9,
            'shop_id' => 1,
            'evaluation' => 2,
            'comment' => "わさび抜きのネタが欲しかったです。",
            'answer' => 1,
            'updated_at' => "2019-09-11 11:19:11"
        ];
        DB::table('evaluations')->insert($param);
        $param = [
            'user_id' => 10,
            'shop_id' => 1,
            'evaluation' => 3,
            'comment' => null,
            'answer' => 1,
            'updated_at' => "2022-01-11 11:15:11"
        ];
        DB::table('evaluations')->insert($param);
        $param = [
            'user_id' => 11,
            'shop_id' => 1,
            'evaluation' => 1,
            'comment' => null,
            'answer' => 1,
            'updated_at' => "2017-04-11 11:19:01"
        ];
        DB::table('evaluations')->insert($param);
        $param = [
            'user_id' => 12,
            'shop_id' => 1,
            'evaluation' => 3,
            'comment' => null,
            'answer' => 1,
            'updated_at' => "2015-08-12 10:19:11"
        ];
        DB::table('evaluations')->insert($param);
        $param = [
            'user_id' => 2,
            'shop_id' => 2,
            'evaluation' => 4,
            'comment' => "今度友達連れて行きます。",
            'answer' => 1,
            'updated_at' => "2020-04-18 15:12:13"
        ];
        DB::table('evaluations')->insert($param);
        $param = [
            'user_id' => 3,
            'shop_id' => 2,
            'evaluation' => 4,
            'comment' => "美味しかったです。ありがとうございました。",
            'answer' => 1,
            'updated_at' => "2021-08-10 05:22:23"
        ];
        DB::table('evaluations')->insert($param);
        $param = [
            'user_id' => 22,
            'shop_id' => 1,
            'answer' => 0,
            'updated_at' => "2020-06-08 23:10:00"
        ];
        DB::table('evaluations')->insert($param);
        $param = [
            'user_id' => 22,
            'shop_id' => 2,
            'answer' => 0,
            'updated_at' => "2016-02-08 13:15:09"
        ];
        DB::table('evaluations')->insert($param);
    }
}
