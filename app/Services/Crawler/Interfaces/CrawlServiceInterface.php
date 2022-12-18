<?php

namespace App\Services\Crawler\Interfaces;

use App\Services\ServiceResult;

interface CrawlServiceInterface
{
    public function search(int $perPage, array $conditions): ServiceResult;

    public function craw(string $url): ServiceResult;

    public function get(int $id): ServiceResult;
}
