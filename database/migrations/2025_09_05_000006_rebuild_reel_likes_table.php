<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class RebuildReelLikesTable extends Migration
{
    public function up()
    {
        // Create new table with correct structure
        Schema::create('reel_likes_new', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reel_id');
            $table->foreignId('user_id');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });

        // Copy data from old table
        DB::statement('INSERT INTO reel_likes_new (id, reel_id, user_id) SELECT id, reel_id, user_id FROM reel_likes');

        // Drop old table
        Schema::drop('reel_likes');

        // Rename new table to old name
        Schema::rename('reel_likes_new', 'reel_likes');
    }

    public function down()
    {
        // No down migration as this is a fix
    }
}
