<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, let's get the current likes data to preserve it
        $likes = DB::table('reel_likes')->get();
        
        // Drop the existing table
        Schema::dropIfExists('reel_likes');

        // Recreate the table with correct structure
        Schema::create('reel_likes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('reel_id')->unsigned();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->unique(['user_id', 'reel_id']);

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('reel_id')
                ->references('id')
                ->on('reels')
                ->onDelete('cascade');
        });

        // Restore the likes data
        foreach ($likes as $like) {
            DB::table('reel_likes')->insert([
                'user_id' => $like->user_id,
                'reel_id' => $like->reel_id,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reel_likes');
    }
};
