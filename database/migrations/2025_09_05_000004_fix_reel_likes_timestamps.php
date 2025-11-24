<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixReelLikesTimestamps extends Migration
{
    public function up()
    {
        // First, ensure the table structure is correct
        DB::unprepared('
            ALTER TABLE reel_likes 
            MODIFY COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            MODIFY COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
        ');
    }

    public function down()
    {
        // No down migration needed as we're fixing existing columns
    }
}
