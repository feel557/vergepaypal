<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CronHistoricalTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cronjob:historicaltest';

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
        
		$trader = new \App\Http\Controllers\ExternalApi\TradeController();
		$trader->getCronHistoricalTest();
		
    }
}
