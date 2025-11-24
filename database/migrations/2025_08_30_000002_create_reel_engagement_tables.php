<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReelEngagementTables extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('reel_likes')) {
            Schema::create('reel_likes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('reel_id');
                $table->foreignId('user_id');
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            });
        }

        if (!Schema::hasTable('reel_comments')) {
            Schema::create('reel_comments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('reel_id');
                $table->foreignId('user_id');
                $table->text('comment');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('reel_reports')) {
            Schema::create('reel_reports', function (Blueprint $table) {
                $table->id();
                $table->foreignId('reel_id');
                $table->foreignId('user_id');
                $table->text('reason')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('reel_reports');
        Schema::dropIfExists('reel_comments');
        Schema::dropIfExists('reel_likes');
    }
}
