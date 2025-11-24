<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ModifyReelLikesTableForTimestamps extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE reel_likes ADD COLUMN IF NOT EXISTS created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP');
        DB::statement('ALTER TABLE reel_likes ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
    }

    public function down()
    {
        DB::statement('ALTER TABLE reel_likes DROP COLUMN IF EXISTS created_at');
        DB::statement('ALTER TABLE reel_likes DROP COLUMN IF EXISTS updated_at');
    }
}
