<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CronPhoneVerificationCleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cronjob:phoneverificationcleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'description';

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
     * @return mixed
     */
    public function handle()
    {
        
		$cronclass = new \App\Http\Controllers\Orders\OrdersProcessingController();
		$cronclass->cronDeletePhoneVerifications();
		
    }
}
