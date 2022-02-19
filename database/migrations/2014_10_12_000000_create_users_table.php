<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->unique()->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->tinyInteger('authority')->default(3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
        // Schema::table('users', function (Blueprint $table) {
        //     $table->dropColumn('verify_email');
        //     $table->dropColumn('verify_token');
        //     $table->dropColumn('verify_date');
        //     $table->dropColumn('verify_email_address');
        // });
    }
}
