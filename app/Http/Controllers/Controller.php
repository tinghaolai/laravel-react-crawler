<?php

namespace App\Http\Controllers;

use App\Services\ServiceResult;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

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
