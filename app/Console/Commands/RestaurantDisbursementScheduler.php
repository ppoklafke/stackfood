<?php

namespace App\Console\Commands;

use App\Models\BusinessSetting;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;

class RestaurantDisbursementScheduler extends Command
{
    protected $signature = 'restaurant:disbursement';
    protected $description = 'Restaurant disbursement scheduling based on business settings';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        app('App\Http\Controllers\Admin\RestaurantDisbursementController')->generate_disbursement();
        $this->info('Restaurant disbursement scheduler executed successfully.');
    }
}
