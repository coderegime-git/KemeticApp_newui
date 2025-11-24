<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AlterReelLikesTimestampColumns extends Migration
{
    public function up()
    {
        // First, modify the columns to allow NULL temporarily
        DB::statement('ALTER TABLE reel_likes MODIFY created_at DATETIME NULL');
        DB::statement('ALTER TABLE reel_likes MODIFY updated_at DATETIME NULL');

        // Then set the default values
        DB::statement('ALTER TABLE reel_likes 
            MODIFY created_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
            MODIFY updated_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
    }

    public function down()
    {
        // Revert changes if needed
        DB::statement('ALTER TABLE reel_likes 
            MODIFY created_at TIMESTAMP NULL,
            MODIFY updated_at TIMESTAMP NULL');
    }
}
