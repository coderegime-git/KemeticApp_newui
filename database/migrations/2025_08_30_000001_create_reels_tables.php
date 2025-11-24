<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Drop tables if they exist in reverse order of dependencies
        Schema::dropIfExists('reel_views');
        Schema::dropIfExists('reel_reports');
        Schema::dropIfExists('reel_comments');
        Schema::dropIfExists('reel_likes');
        Schema::dropIfExists('reels');

        Schema::create('reels', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('title')->nullable();
            $table->string('caption', 300)->nullable();
            $table->string('video_path');
            $table->string('thumbnail_path')->nullable();
            $table->string('processed_video_path')->nullable();
            $table->boolean('is_processed')->default(false);
            $table->boolean('is_hidden')->default(false);
            $table->integer('duration')->nullable(); // in seconds
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('likes_count')->default(0);
            $table->unsignedInteger('comments_count')->default(0);
            $table->unsignedInteger('reports_count')->default(0);
            $table->integer('created_at');
            $table->integer('updated_at')->nullable();
            $table->integer('deleted_at')->nullable();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->index(['user_id', 'created_at']);
            $table->index('is_hidden');
        });

        Schema::create('reel_likes', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('reel_id')->unsigned();
            $table->integer('created_at');
            
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('reel_id')
                ->references('id')
                ->on('reels')
                ->onDelete('cascade');
            
            $table->unique(['user_id', 'reel_id']);
            $table->index(['reel_id', 'created_at']);
        });

        Schema::create('reel_comments', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('reel_id')->unsigned();
            $table->text('content');
            $table->integer('created_at');
            $table->integer('updated_at')->nullable();
            $table->integer('deleted_at')->nullable();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('reel_id')
                ->references('id')
                ->on('reels')
                ->onDelete('cascade');

            $table->index(['reel_id', 'created_at']);
        });

        Schema::create('reel_reports', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('reel_id')->unsigned();
            $table->string('reason');
            $table->text('description')->nullable();
            $table->integer('created_at');
            
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('reel_id')
                ->references('id')
                ->on('reels')
                ->onDelete('cascade');
            
            $table->unique(['user_id', 'reel_id']);
            $table->index(['reel_id', 'created_at']);
        });

        Schema::create('reel_views', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('reel_id')->unsigned();
            $table->integer('created_at');
            
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('reel_id')
                ->references('id')
                ->on('reels')
                ->onDelete('cascade');
            
            $table->unique(['user_id', 'reel_id']);
            $table->index(['reel_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('reel_views');
        Schema::dropIfExists('reel_reports');
        Schema::dropIfExists('reel_comments');
        Schema::dropIfExists('reel_likes');
        Schema::dropIfExists('reels');
    }
};
