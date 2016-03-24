<?php

namespace App\Console\Commands;

use App\Crawlers\Main;
use App\Crawlers\ProxyChecker;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Symfony\Component\DomCrawler\Crawler;

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

    /*
     * Crawler through proxy
     */

    public function throughProxy($url, $proxy)
    {
        //proxy = '218.161.34.107:8080';
       return Main::crawlerLink($url,[ 'proxy' => $proxy, 'debug' => true ]);
    }

    /*
     * Get Amazon Review in Frame Var Javascript
     */
    public function getAmazonReviews($url)
    {
        $output = Main::crawlerLink($url);
        $output = explode('var iframeContent = "', $output);
        $output1 = explode('";', $output[1]);
        $crawler = new  Crawler(urldecode($output1[0]));
        $reviews = [
            'sources' => [],
            'texts' => []
        ];
        $sourceHtmls = $crawler->filter('div.content > h3.productDescriptionSource');

        if (iterator_count($sourceHtmls) > 0) {
            foreach ($sourceHtmls as $sourceHtml) {
                $temp = new Crawler($sourceHtml);
                $reviews['sources'][] = $temp->text();
            }
        }

        $textHtmls = $crawler->filter('div.content > div.productDescriptionWrapper');

        if (iterator_count($textHtmls) > 0) {
            foreach ($textHtmls as $textHtml) {
                $temp = new Crawler($textHtml);
                $reviews['texts'][] = $temp->text();
            }
        }

        return $reviews;

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {


        $url = "http://www.amazon.com/gp/product/0767922476/ref=s9_simh_gw_g14_i3_r?ie=UTF8&fpl=fresh&pf_rd_m=ATVPDKIKX0DER&pf_rd_s=desktop-1&pf_rd_r=1WPRC154AFQT2JQMSF4C&pf_rd_t=36701&pf_rd_p=2437869742&pf_rd_i=desktop";

    }
}
