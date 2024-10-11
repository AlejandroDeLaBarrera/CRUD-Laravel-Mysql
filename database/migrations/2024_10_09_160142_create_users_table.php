<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration {

    public function up(){
        Schema::create('users', function (Blueprint $table){
            $table->id();
            $table->string('email')->unique();
            $table->tinyInteger('status_id');
            $table->tinyInteger('profile_id');
            $table->text('password');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('users');
    }
}
