<?php

namespace App\Services\Crawler;

use App\Repositories\Interfaces\CrawlRepositoryInterface;
use App\Services\Crawler\Interfaces\CrawlServiceInterface;
use App\Services\ServiceResult;

class CrawlerService implements CrawlServiceInterface
{
    private CrawlRepositoryInterface $crawlRepository;

    public function __construct(CrawlRepositoryInterface $crawlRepository)
    {
        $this->crawlRepository = $crawlRepository;
    }

    public function search(int $perPage, array $conditions): ServiceResult
    {
        return $this->crawlRepository->search($perPage, $conditions);
    }

    public function craw(string $url): ServiceResult
    {
        return $this->crawlRepository->craw($url);
    }

    public function get(int $id): ServiceResult
    {
        return $this->crawlRepository->get($id);
    }
}
