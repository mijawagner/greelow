<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->text('text');
            $table->string('author'); // This could be a username or could also use user_id if you want to link to users
            $table->foreignId('post_id')->constrained()->onDelete('cascade'); // Link to post
            $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade'); // Self-referencing for nested comments
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
