<?php

namespace App\Http\Controllers\Crawler;

use App\Http\Controllers\Controller;
use App\Services\Crawler\CrawlerService;
use App\Services\Crawler\CrawlerUrlService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CrawlerController extends Controller
{
    /**
     * Search crawler paginate results
     *
     * @param Request $request
     * @param CrawlerService $crawlerService
     * @return JsonResponse
     */
    public function index(Request $request, CrawlerService $crawlerService): JsonResponse
    {
        return $this->handleServiceResult(
            $crawlerService->search($request->input('perPage') ?? 10, $request->all())
        );
    }

    /**
     * Crawl single url, return id crated
     *
     * @param Request $request
     * @param CrawlerService $crawlerService
     * @param CrawlerUrlService $crawlerUrlService
     * @return JsonResponse
     */
    public function store(
        Request $request,
        CrawlerService $crawlerService,
        CrawlerUrlService $crawlerUrlService
    ): JsonResponse {
        if (!$request->has('url')) {
            return $this->inValidParam('missing url');
        }

        $crawlerUrlService->setUrl($request->input('url'));
        return $this->handleServiceResult($crawlerService->craw(
            $request->input('url'),
            $crawlerUrlService
        ));
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
