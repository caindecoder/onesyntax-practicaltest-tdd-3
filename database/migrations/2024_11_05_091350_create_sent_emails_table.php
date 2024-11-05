<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSentEmailsTable extends Migration
{
    public function up()
    {
        Schema::create('sent_emails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->onDelete('cascade'); // Reference to the post
            $table->string('recipient_email');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sent_emails');
    }
}
