<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSentToPostsTable extends Migration
{
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->boolean('sent')->default(false);
        });
    }
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('sent');
        });
    }
}
