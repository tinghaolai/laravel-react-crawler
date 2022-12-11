<?php

namespace App\Services\Crawler;

use Psr\Http\Message\UriInterface;
use Spatie\Crawler\CrawlProfiles\CrawlProfile;

class SingleUrlCrawlerService extends CrawlProfile
{
    protected string $url;

    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Crawl only target url
     *
     * @param UriInterface $url
     * @return bool
     */
    public function shouldCrawl(UriInterface $url): bool
    {
        return $this->url == $url;
    }
}
