<?php

namespace Tests\Unit\Services\Crawler;

use App\Services\Crawler\SingleUrlCrawlerService;
use Tests\TestCase;
use Psr\Http\Message\UriInterface;

class SingleUrlCrawlerServiceTest extends TestCase
{
    /**
     * Test function should return false when urls aren't the same
     *
     * @return void
     * @throws \ReflectionException
     */
    public function test_shouldCrawl_not_same_url_return_false(): void
    {
        $url = \Mockery::mock(UriInterface::class);
        $url->shouldReceive('__toString')->andReturn('test-url-not-same');

        $service = new SingleUrlCrawlerService();
        $this->setProperties($service, ['url' => 'test-url']);
        $result = $service->shouldCrawl($url);
        $this->assertFalse($result);
    }


    /**
     * Test funciton should return true when urls are the same
     *
     * @return void
     * @throws \ReflectionException
     */
    public function test_shouldCrawl_not_same_url_return_true(): void
    {
        $url = \Mockery::mock(UriInterface::class);
        $url->shouldReceive('__toString')->andReturn('test-url');

        $service = new SingleUrlCrawlerService();
        $this->setProperties($service, ['url' => 'test-url']);
        $result = $service->shouldCrawl($url);
        $this->assertTrue($result);
    }
}
