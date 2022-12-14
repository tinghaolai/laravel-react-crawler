<?php

namespace App\Http\Controllers\Crawler;

use App\Http\Controllers\Controller;
use App\Services\Crawler\Interfaces\CrawlServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CrawlerController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/crawler",
     *      operationId="api/crawler/index",
     *      tags={"cralwer"},
     *      summary="Cralwer index api",
     *      description="search crawler results with optional paramters",
     *      @OA\Parameter(
     *          name="perPage",
     *          description="paginate size",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="page",
     *          description="paginate search page",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="tile",
     *          description="crawler url result title fuzzy search",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="description",
     *          description="crawler url result description fuzzy search",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="createdAt[]",
     *          description="search crawler result created at date range",
     *          required=false,
     *          in="query",
 *              @OA\Schema(
     *              type="array",
     *              @OA\Items(type="timestamp"),
     *              example={"2022-12-12 00:00:00","2022-12-12 00:00:01"}
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="paginate result"
     *       ),
     * )
     */
    public function index(Request $request, CrawlServiceInterface $crawlerService): JsonResponse
    {
        return $this->handleServiceResult(
            $crawlerService->search($request->input('perPage') ?? 10, $request->all())
        );
    }

    public function store(Request $request, CrawlServiceInterface $crawlerService): JsonResponse
    {
        if (!$request->has('url')) {
            return $this->inValidParam('missing url');
        }

        return $this->handleServiceResult($crawlerService->craw(
            $request->input('url')
        ));
    }

    public function show($id, CrawlServiceInterface $crawlerService): JsonResponse
    {
        return $this->handleServiceResult($crawlerService->get($id));
    }
}
