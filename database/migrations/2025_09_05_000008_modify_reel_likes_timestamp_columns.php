<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ModifyReelLikesTimestampColumns extends Migration
{
    public function up()
    {
        // Drop existing timestamps first
        Schema::table('reel_likes', function (Blueprint $table) {
            $table->dropColumn(['created_at', 'updated_at']);
        });

        // Add them back with correct type
        DB::unprepared('
            ALTER TABLE reel_likes 
            ADD COLUMN created_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
            ADD COLUMN updated_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ');
    }

    public function down()
    {
        // If needed to rollback
        Schema::table('reel_likes', function (Blueprint $table) {
            $table->dropColumn(['created_at', 'updated_at']);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
}
