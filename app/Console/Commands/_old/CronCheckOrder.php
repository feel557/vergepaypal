<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CronCheckOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cronjob:checkorder';

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
        
		$bittrex = new \App\Http\Controllers\ExternalApi\TradeController();
		$bittrex->getCheckOrderStatus();
		
    }
}
