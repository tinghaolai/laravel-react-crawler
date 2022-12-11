<?php

namespace App\Services\Crawler;

use App\Models\Crawler as CrawlerModel;
use App\Services\ServiceResult;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use JetBrains\PhpStorm\ArrayShape;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Spatie\Browsershot\Browsershot;
use Spatie\Crawler\CrawlObservers\CrawlObserver;
use Spatie\Crawler\Crawler;

class CrawlerService extends CrawlObserver
{
    public function get($id): ServiceResult
    {
        if (!($crawler = CrawlerModel::find($id))) {
            return ServiceResult::failResult(['message' => 'not found']);
        }

        return ServiceResult::successResult($this->apiFormat($crawler));
    }

    public function craw(string $url): ServiceResult
    {
        Crawler::create(['verify' => false])
            ->executeJavaScript()
            ->setCrawlProfile(new CrawlerUrlService($url))
            ->setCrawlObserver($this)
            ->startCrawling($url);

        $crawler = CrawlerModel::where('url', $url)->first();
        if (!$crawler) {
            return ServiceResult::failResult();
        }

        return ServiceResult::successResult(['id' => $crawler->id]);
    }

    public function crawled(UriInterface $url, ResponseInterface $response, ?UriInterface $foundOnUrl = null): void
    {
        $dom            = new \DOMDocument('1.0', 'UTF-8');
        $internalErrors = libxml_use_internal_errors(true);
        $html           = (string)$response->getBody();
        $dom->loadHTML($html);
        libxml_use_internal_errors($internalErrors);
        $titles      = $dom->getElementsByTagName('title');
        $bodies      = $dom->getElementsByTagName('body');
        $metas       = $dom->getElementsByTagName('meta');
        $description = null;

        foreach ($metas as $meta) {
            $meta->getAttribute('name');
            if ($meta->getAttribute('name') === 'description') {
                $description = $meta->getAttribute('content');
                break;
            }
        }

        $crawl = CrawlerModel::updateOrCreate([
            'url' => $url,
        ], [
            'title'       => $titles->item(0)->nodeValue ?? null,
            'body'        => $bodies->item(0)->nodeValue ?? null,
            'description' => $description,
            'origin_html' => $html,
        ]);

        try {
            $parseUrl    = parse_url($url);
            $queryString = empty($parseUrl['query']) ? '' : ('-' . $parseUrl['query']);
            $fileName    = str_replace('.', '-', $parseUrl['host'] . $queryString) . '.jpg';
            $pathName    = storage_path('/app/public/' . $fileName);

            Browsershot::url($url)->save($pathName);
            $crawl->update([
                'screen_shot_path' => Storage::url($fileName)
            ]);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
        }
    }

    public function crawlFailed(
        UriInterface $url,
        RequestException $requestException,
        ?UriInterface $foundOnUrl = null
    ): void {
        Log::info('crawler fail');
        Log::info($requestException->getMessage());
    }


    #[ArrayShape([
        'url'            => "mixed",
        'title'          => "mixed",
        'description'    => "mixed",
        'screenShotPath' => "mixed",
        'body'           => "mixed"
    ])] private function apiFormat(CrawlerModel $crawler): array
    {
        return [
            'url'            => $crawler->getAttribute('url'),
            'title'          => $crawler->getAttribute('title'),
            'description'    => $crawler->getAttribute('description'),
            'screenShotPath' => $crawler->getAttribute('screen_shot_path'),
            'body'           => $crawler->getAttribute('body'),
        ];
    }
}
