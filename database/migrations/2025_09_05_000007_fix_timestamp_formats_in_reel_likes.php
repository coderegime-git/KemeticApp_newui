<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixTimestampFormatsInReelLikes extends Migration
{
    public function up()
    {
        DB::unprepared('
            ALTER TABLE reel_likes 
            MODIFY created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            MODIFY updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
        ');
    }

    public function down()
    {
        // No down migration needed as this is a fix
    }
}
