<?php

namespace App\Console\Commands;

use App\Crawlers\Main;
use App\Crawlers\ProxyChecker;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class IncreaseTraffic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'increase:traffic {--ip=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Increase traffic';

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
        $url = 'http://medicinebooklet.com';
        $response = Main::crawlerLink($url,[ 'proxy' => '218.161.34.107:8080', 'debug' => true ]);
        $this->line('ooke');
    }
}
