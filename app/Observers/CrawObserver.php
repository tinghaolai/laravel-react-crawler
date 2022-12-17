<?php

namespace App\Observers;

use App\Models\Crawler as CrawlerModel;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Spatie\Browsershot\Browsershot;
use Spatie\Crawler\CrawlObservers\CrawlObserver as BaseObserver;

class CrawObserver extends BaseObserver
{
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
}
