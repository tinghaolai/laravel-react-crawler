<?php

namespace App\Http\Controllers;

use App\Services\ServiceResult;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Laravel-react-cralwer api",
 *      description=" Api documentation",
 *      @OA\Contact(
 *          email="test@test.com"
 *      ),
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="API Server"
 * )

 *
 * @OA\Tag(
 *     name="Projects",
 *     description=" Api Endpoints"
 * )
 * @OA\Schemes(format="http")
 * @OAS\SecurityScheme(
 *      securityScheme="bearer_token",
 *      type="http",
 *      scheme="bearer"
 * )
 */

class Controller extends BaseController
{
    use ApiResponse;
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    /**
     * Convert api response from ServiceResult
     *
     * @param ServiceResult $result
     * @return JsonResponse
     */
    public function handleServiceResult(ServiceResult $result): JsonResponse
    {
        if ($result->fail()) {
            return $this->fail('fail crawling', $result->data());
        }

        return $this->success('success', $result->data());
    }
}
