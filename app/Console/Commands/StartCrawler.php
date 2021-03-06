<?php

namespace App\Console\Commands;

use App\Category;
use App\Crawlers\Main;
use App\Download;
use App\Keyword;
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
    protected $signature = 'crawler:start {--insert=} {--limit=} {--download=} {--key=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = ' php crawler:start --insert="key 1, key 2" || php crawler:start  --limit (or no need --limit) ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    protected function googleBook($limit, $keyword)
    {
        set_time_limit(0);

        $url = 'https://www.googleapis.com/books/v1/volumes?q='.$keyword.'&printType=books&projection=full&orderBy=newest';
        
        $urlDetails = Main::crawlerLink($url);
        
        $urlDetails = json_decode($urlDetails, true);
        
        if (isset($urlDetails['totalItems'])) {
            $totalItems = (int) $urlDetails['totalItems'];
            
            if ($totalItems > 0) {
                $maxIndex = round($totalItems/20);
                if ($maxIndex > $limit) {
                    $maxIndex = $limit;
                }
                for ($i = 0; $i < $maxIndex; $i ++) {
                  $startIndex = $i*20;
                  $browserUrl = 'https://www.googleapis.com/books/v1/volumes?q='.$keyword.'&startIndex='.$startIndex.'&maxResults=20&printType=books&projection=full&orderBy=newest';
                   
                
                  $bookDetails = Main::crawlerLink($browserUrl);
                  if ($bookDetails) {
                    $bookDetails = json_decode($bookDetails, true);                       
                    if (isset($bookDetails['items'])) {
                        foreach ($bookDetails['items'] as $item) {      
                            
                            $categoryName = isset($item['volumeInfo']['categories'][0]) ? $item['volumeInfo']['categories'][0] : 'General';
                            $category =  Category::updateOrCreate(
                                ['name' => $categoryName],
                                ['name' => $categoryName, 'parent_id' => null]
                            );
                            
                            $title = isset($item['volumeInfo']['title']) ? $item['volumeInfo']['title'] : '';
                            $subtitle = isset($item['volumeInfo']['subtitle']) ? $item['volumeInfo']['subtitle'] : '';
                            
                            $title = ($title || $subtitle) ? $title. ' '. $subtitle : '';
                            
                            $ibsn = isset($item['volumeInfo']['industryIdentifiers'][0]['identifier']) ? $item['volumeInfo']['industryIdentifiers'][0]['identifier'] : '';
                            
                            $publisher = isset($item['volumeInfo']['publisher']) ? $item['volumeInfo']['publisher'] : '';
                            
                            $release_date = isset($item['volumeInfo']['publishedDate']) ? $item['volumeInfo']['publishedDate'] : ''; 
                            
                            $content = isset($item['volumeInfo']['description']) ? $item['volumeInfo']['description'] : '';
                            
                            $pages = isset($item['volumeInfo']['pageCount']) ? $item['volumeInfo']['pageCount'] : 500;
                            
                            $image = isset($item['volumeInfo']['imageLinks']['thumbnail']) ? $item['volumeInfo']['imageLinks']['thumbnail'] : '';
                            
                            $authors = isset($item['volumeInfo']['authors']) ? $item['volumeInfo']['authors'] : [];
                            
                            $preview = isset($item['accessInfo']['webReaderLink']) ? $item['accessInfo']['webReaderLink'] : '';
                            
                            
                            if ($title && $publisher && $release_date && $content && $image && $authors && $preview && $ibsn) {
                                                               
                                 Post::updateOrCreate(['title' => $title], [
                                    'title' => $title,
                                    'category_id' => $category->id,
                                    'desc' => $content,
                                    'content' => $content,
                                    'publisher' => $publisher,
                                    'release_date' => $release_date,
                                    'author' => implode(',', $authors),
                                    'image' => $image,
                                    'ibns' => $ibsn,
                                    'preview' => $preview,
                                    'pages' => $pages,
                                    'status' => true
                                 ]);
                            }
                               
                        }
                    }                   
                } 
               }
            } 
        }
    }



    public function getDownloads($key = 0)
    {
        $apiList = [
            'AIzaSyAWH9J9ZUHyGBm7G8uoKbqykEnL-ZzHdVU',
            'AIzaSyDyxq9nrhKA6alpTcWWcuer249NwOCxZ6w',
            'AIzaSyAghqYRlZ1CqUqG2kqAjGCKyZxEpfCNfps',
            'AIzaSyChK6MK61ZHXrY8GDhR9sRUorIGUDVb150',
            'AIzaSyD6UeIP6Hu78rtrDrj3cKhLosQ7EG5QgLQ',
            'AIzaSyB_q6eumkufXePU9kDPWpsInMTVeGrBN_A',
            'AIzaSyAxxlPSbQHBGio4MvqQP7-4p7KtR7Kg8ws',
            'AIzaSyCq0DdrHku3vVWRhZhgWBe3ABCtu1kL1JA',
            //'AIzaSyB4lI0zqMcqLa1bmMwzqenu9kgieb-ZKW8' for application browser.
        ];
        $posts = Post::where('download', false)->orderBy('id', 'asc')->limit(100)->get();
        foreach ($posts as $post) {
            $escape_title = urlencode($post->title);


            $download_url = 'https://www.googleapis.com/customsearch/v1?key='.$apiList[$key].'&cx=007984933932694601763:shxevrgfvso&c2coff=0&q='.$escape_title.'&hl=en&fileType=pdf';
            $searchDetails = Main::crawlerLink($download_url);
            $urlDetails = json_decode($searchDetails, true);
            $update = false;
            if (isset($urlDetails['items'])) {
                foreach ($urlDetails['items'] as $item) {
                    if (isset($item['link']) && Main::isPdfUrl($item['link'])) {
                        $update = true;
                        Download::create([
                            'post_id' => $post->id,
                            'link' => $item['link'],
                            'title' => $item['title']
                        ]);
                    }
                }
            }

            if ($update) {
                $post->download = true;
                $post->save();
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
        if ($this->option('insert')) {
            $keywords = $this->option('insert');
            $keywords = explode(', ' , $keywords);
            foreach ($keywords as $key) {
                Keyword::updateOrCreate(['keyword' => $key], [
                    'keyword' => $key
                ]);
            }
        } else if ($this->option('download')) {
            $key = $this->option('key')? $this->option('key') : 0;
            $this->getDownloads($key);
        } else {
            $keyword = Keyword::where('using', false)->get();
            if ($keyword->count() > 0) {
                $keyToWork = $keyword->first();
                $limit = $this->option('limit') ? (int) $this->option('limit') : 100000;
                $this->googleBook($limit, $keyToWork->keyword);
                $keyToWork->using = true;
                $keyToWork->save();
            } else {
                $this->line('All keywords already used!');
            }
        }

    }
}
