<?php
/**
 * Created by PhpStorm.
 * User: MANHQUAN
 * Date: 3/9/2015
 * Time: 10:57 PM
 */

namespace App\Crawlers;



use App\Http\Requests;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use Intervention\Image\Exception\NotReadableException;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;
use Symfony\Component\DomCrawler\Crawler;


class Main {

    public static function isPdfUrl($url)
    {
        $path = parse_url($url, PHP_URL_PATH);
        $ext = pathinfo($path, PATHINFO_EXTENSION);

        if (strtolower($ext) == 'pdf') {
            return true;
        }
        return false;
    }

    /**
     * save image to public.
     * @param $url
     * @param $case
     * @return string
     */
    protected function saveImageFromLink($url)
    {
        $ars = explode('.', $url);
        $ext = end($ars);
        if (in_array($ext, ['jpg', 'png', 'jpeg', 'gif'])) {
            $name = md5(time()) . '.' . end($ars);
        } else {
            $name = md5(time()) . '.png';
        }
        try {
            Image::make($url)->save(public_path('files/'. $name));
        } catch (NotReadableException $e) {
            return;
        }
        return $name;
    }


    /**
     * crawl a link     *
     * @param $link
     * @param bool $redirect
     * @return string
     */
    public static function crawlerLink($link)
    {
        set_time_limit(0);

        try {
            $client = new Client();
            $response = $client->request('GET', $link);
            if ($response->getStatusCode() == '200') {
                return $response->getBody()->getContents();
            }
        } catch (TransferException $e) {
            return;
        }
    }



    /**
     * crawl details page by package name on google store.
     * @param $package
     * @return CrawlerController|void
     */
    public static function example($package)
    {
        $link = 'https://play.google.com/store/apps/details?id=' . $package . '&hl=en&gl=us';
        $response = self::crawlerLink($link);
        if (!$response) {
            return;
        }
        $crawler = new  Crawler($response);

        $data = [];
        $temp = $crawler->filter('div.details-info > div.cover-container > img.cover-image');
        if (!$temp) {
            return;
        } else {
            $data['icon'] = $temp->attr('src');
        }

        $data['title'] = $crawler->filter('div.details-info > div.info-container > div.document-title > div')->text();
        $type = $crawler->filter('div.details-info > div.info-container a.category')->attr('href');

        $data['type'] = (strpos($type, 'GAME_') !== false) ? 'games' : 'apps';
        $data['category'] = $crawler->filter('div.details-info > div.info-container a.category > span')->text();
        $data['screens'] = [];
        $captures = $crawler->filter('div.screenshots img.screenshot');
        if (iterator_count($captures) > 0) {
            foreach ($captures as $capture) {
                $temp = new Crawler($capture);
                $data['screens'][] = $temp->attr('src');
            }
        }

        $tempMeta = $crawler->filter('div.metadata div.details-section-contents > div.meta-info');

        if (iterator_count($tempMeta) > 0) {
            foreach ($tempMeta as $i => $meta) {
                $tempUpdate = new Crawler($meta);
                if ($i == 0) {
                    $data['update'] = $tempUpdate->filter('div.content')->text();
                    $data['update'] = Carbon::parse($data['update'])->format('Y-m-d');
                }
                if ($i == 1) {
                    $data['total'] = $tempUpdate->filter('div.content')->text();
                    $data['total'] = trim($data['total']);
                }
                if ($i == 3) {
                    $data['version'] = $tempUpdate->filter('div.content')->text();
                    $data['version'] = trim($data['version']);
                }
                if ($i == 4) {
                    $data['require'] = $tempUpdate->filter('div.content')->text();
                    $data['require'] = trim($data['require']);
                }

            }
        }

        $data['desc'] = $crawler->filter('div.details-section-contents div.id-app-orig-desc')->html();
        $data['news'] = $crawler->filter('div.whatsnew div.recent-change');
        if (count($data['news'])) {
            $data['news'] = $data['news']->html();
        } else {
            $data['news'] = '';
        }
        $data['link'] = $link;
        $data['site'] = 'https://play.google.com';
        $data['download'] = $link;
    }


}