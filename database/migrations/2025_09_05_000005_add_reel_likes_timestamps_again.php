<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddReelLikesTimestampsAgain extends Migration
{
    public function up()
    {
        if (Schema::hasTable('reel_likes')) {
            // First add columns if they don't exist
            if (!Schema::hasColumn('reel_likes', 'created_at')) {
                DB::unprepared('ALTER TABLE reel_likes ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
            }
            if (!Schema::hasColumn('reel_likes', 'updated_at')) {
                DB::unprepared('ALTER TABLE reel_likes ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
            }
            
            // Then modify them to ensure correct defaults
            DB::unprepared('
                ALTER TABLE reel_likes 
                MODIFY created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                MODIFY updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ');
        }
    }

    public function down()
    {
        Schema::table('reel_likes', function (Blueprint $table) {
            $table->dropColumn(['created_at', 'updated_at']);
        });
    }
}
