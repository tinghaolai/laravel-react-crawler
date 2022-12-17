<?php

namespace App\Profiles;

use Psr\Http\Message\UriInterface;
use Spatie\Crawler\CrawlProfiles\CrawlProfile;
use App\Profiles\Interfaces\CrawlProfileInterface;

class CrawlSingleUrlProfile extends CrawlProfile implements CrawlProfileInterface
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
