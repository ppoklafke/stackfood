<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Madnest\Madzipper\Facades\Madzipper;
use App\CentralLogics\Helpers;

class DatabaseRefresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh database after a certain time';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

    try {
        $data=[
            'title' => 'demo_reset',
            'description' => 'demo_reset',
            'image' => '',
            'order_id' => '',
            'type' => 'stackfood_demo_reset',
        ];
        Helpers::send_push_notif_for_demo_reset($data, $data['type'], 'stackfood_demo_reset');
    } catch (\Throwable $th) {
        info('Failed_to_sent_demo_reset_notification');
    }
        Artisan::call('db:wipe');
        $sql_path = base_path('installation/backup/database.sql');
        DB::unprepared(file_get_contents($sql_path));
        File::deleteDirectory('storage/app/public');
        Madzipper::make('installation/backup/public.zip')->extractTo('storage/app');
    }
}
