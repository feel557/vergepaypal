<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CronCheckBuyOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cronjob:buyorder';

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
		$bittrex->getCheckBuy(); //takes 2 seconds to run
			sleep(18); //sleep for 18 seconds to ensure we are only running the command every 20 seconds
		$bittrex->getCheckBuy(); //takes 2 seconds to run
			sleep(18);
		$bittrex->getCheckBuy();
		
    }
}
