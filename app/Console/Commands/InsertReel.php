<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InsertReel extends Command
{
    protected $signature = 'reel:insert';
    protected $description = 'Insert a record for Bird_nest.mp4 into the reels table';

    public function handle()
    {
        $now = time();
        
        $id = DB::table('reels')->insertGetId([
            'user_id' => 28038,
            'title' => 'Bird Nest Video',
            'caption' => 'A beautiful video of a bird nest',
            'video_path' => 'Bird_nest.mp4',
            'is_processed' => true,
            'is_hidden' => false,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $this->info('Reel created with ID: ' . $id);
        
        $reel = DB::table('reels')->find($id);
        $this->table(
            ['ID', 'Title', 'Video Path', 'Created At'],
            [[$reel->id, $reel->title, $reel->video_path, date('Y-m-d H:i:s', $reel->created_at)]]
        );
    }
}
