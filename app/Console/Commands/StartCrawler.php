<?php

namespace App\Console\Commands;

use App\Category;
use App\Crawlers\Main;
use App\Post;
use Illuminate\Console\Command;
use Symfony\Component\DomCrawler\Crawler;

class StartCrawler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler:start {keyword?} {--limit=} {--insert} {--max=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start Craw Data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    protected function insertLinks($maxPage, $keyword)
    {
        $books = [];

        for ($i = 1; $i <= $maxPage; $i ++) {
            $browserUrl = 'http://www.amazon.com/gp/search/ref=sr_pg_2?rh=n%3A283155%2Ck%3A'.$keyword.'%2Cp_n_feature_browse-bin%3A2656022011&page='.$i.'&bbn=283155&keywords='.$keyword.'&ie=UTF8&qid=1457062909';

            $response = Main::crawlerLink($browserUrl);
            if ($response) {
                $crawler = new  Crawler($response);
                $captures = $crawler->filter('li.s-result-item a.s-access-detail-page');
                if (iterator_count($captures) > 0) {
                    foreach ($captures as $capture) {
                        $temp = new Crawler($capture);
                        $books[] = [
                            'link' => $temp->attr('href'),
                            'page' => $i,
                            'keyword' => $keyword
                        ];
                    }
                }
            }
        }

        ///update to database.
        if ($books) {
            \App\Crawler::insert($books);
        }
    }

    protected function crawlBook($limit)
    {
        $crawlerDatabases = \App\Crawler::where('post_id', 0)->limit($limit)->get();

        foreach ($crawlerDatabases as $crawlerDatabase) {

            $tempLinks = explode('/', $crawlerDatabase->link);
            $index = 0;
            foreach ($tempLinks as $k => $value) {
                if ($value == 'dp') {
                    $index = $k;
                }
            }
            if (isset($tempLinks[$index + 1])) {
                $number = $tempLinks[$index + 1];
                $googleApi = 'https://www.googleapis.com/books/v1/volumes?q=isbn:'.$number;
                $bookDetails = Main::crawlerLink($googleApi);
                if ($bookDetails) {
                    $bookDetails = json_decode($bookDetails, true);
                    if (
                        isset($bookDetails['items'][0]['volumeInfo']['categories'][0]) &&
                        isset($bookDetails['items'][0]['volumeInfo']['industryIdentifiers'][1]['identifier']) &&
                        isset($bookDetails['items'][0]['volumeInfo']['title']) &&
                        isset($bookDetails['items'][0]['volumeInfo']['publisher']) &&
                        isset($bookDetails['items'][0]['volumeInfo']['publishedDate']) &&
                        isset($bookDetails['items'][0]['volumeInfo']['description']) &&
                        isset($bookDetails['items'][0]['volumeInfo']['pageCount']) &&
                        isset($bookDetails['items'][0]['volumeInfo']['industryIdentifiers'][1]['identifier']) &&
                        isset($bookDetails['items'][0]['volumeInfo']['imageLinks']['thumbnail']) &&
                        isset($bookDetails['items'][0]['volumeInfo']['authors']) &&
                        isset($bookDetails['items'][0]['volumeInfo']['webReaderLink'])
                    ) {
                        $categoryName = $bookDetails['items'][0]['volumeInfo']['categories'][0];
                        $category =  Category::updateOrCreate(
                            ['name' => $categoryName],
                            ['name' => $categoryName, 'parent_id' => null]
                        );

                        $post = Post::updateOrCreate (
                            [
                                'ibsn' => $bookDetails['items'][0]['volumeInfo']['industryIdentifiers'][1]['identifier']
                            ] ,
                            [
                                'category_id' => $category->id,
                                'title' => $bookDetails['items'][0]['volumeInfo']['title'],
                                'publisher' => $bookDetails['items'][0]['volumeInfo']['publisher'],
                                'release_date' => $bookDetails['items'][0]['volumeInfo']['publishedDate'],
                                'content' => $bookDetails['items'][0]['volumeInfo']['description'],
                                'desc' => str_limit($bookDetails['items'][0]['volumeInfo']['description'], 200),
                                'pages' => $bookDetails['items'][0]['volumeInfo']['pageCount'],
                                'ibsn' => $bookDetails['items'][0]['volumeInfo']['industryIdentifiers'][1]['identifier'],
                                'image' => $bookDetails['items'][0]['volumeInfo']['imageLinks']['thumbnail'],
                                'preview' => $bookDetails['items'][0]['volumeInfo']['webReaderLink'],
                                'author' => implode(',', $bookDetails['items'][0]['volumeInfo']['authors']),
                                'status' => true
                            ]);

                        $crawlerDatabase->post_id = $post->id;
                        $crawlerDatabase->save();
                    }
                }
            }
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $keyword = $this->argument('keyword');

        if (!$keyword) {
            $keyword = 'stress';
        }

        if ($this->option('insert')) {
            $maxPage = $this->option('max') ? (int) $this->option('max') : 100;
            $this->insertLinks($maxPage, $keyword);
        } else {
            $limit = $this->option('limit') ? (int) $this->option('limit') : 100;
            $this->crawlBook($limit);
        }

    }
}
