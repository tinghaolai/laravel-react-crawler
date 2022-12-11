<?php

namespace App\Http\Controllers\Crawler;

use App\Http\Controllers\Controller;
use App\Services\Crawler\CrawlerService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CrawlerController extends Controller
{
    /**
     * Crawl single url, return id crated
     *
     * @param Request $request
     * @param CrawlerService $crawlerService
     * @return JsonResponse
     */
    public function store(Request $request, CrawlerService $crawlerService): JsonResponse
    {
        if (!$request->has('url')) {
            return $this->inValidParam('missing url');
        }

        return $this->handleServiceResult($crawlerService->craw($request->input('url')));
    }

    /**
     * Get crawled page info by id
     *
     * @param $id
     * @param CrawlerService $crawlerService
     * @return JsonResponse
     */
    public function show($id, CrawlerService $crawlerService): JsonResponse
    {
        return $this->handleServiceResult($crawlerService->get($id));
    }
}
