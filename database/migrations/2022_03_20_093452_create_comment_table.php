<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('comment_user_id');
            $table->unsignedBigInteger('comment_post_id');
            $table->longText('comment_content');
            // $table->string('comment_status',50);
            $table->enum('comment_status',['Approve','Unapprove'])->default('Unapprove');
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
        Schema::dropIfExists('comment');
    }
}
