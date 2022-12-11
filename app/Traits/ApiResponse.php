<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as FoundationResponse;

trait ApiResponse
{
    /**
     * Response when request parameter are invalid
     *
     * @param string $message
     * @return JsonResponse
     */
    public function inValidParam(string $message = 'invalid parameters'): JsonResponse
    {
        return $this->apiRespond(FoundationResponse::HTTP_BAD_REQUEST, [
            'status'       => 'inValidParam',
            'errorMessage' => $message,
        ]);
    }

    /**
     * Response when api request resource not exists
     *
     * @param string $message
     * @return JsonResponse
     */
    public function notFound(string $message = 'source not found'): JsonResponse
    {
        return $this->apiRespond(FoundationResponse::HTTP_NOT_FOUND, [
            'status'       => 'notFound',
            'errorMessage' => $message,
        ]);
    }

    /**
     * Response when api request success
     *
     * @param string|null $message
     * @param array $response
     * @return JsonResponse
     */
    public function success(string $message = null, array $response = []): JsonResponse
    {
        $response['status'] = 'success';
        if (!is_null($message)) {
            $response['successMessage'] = $message;
        }

        return response()->json($response);
    }

    /**
     * Response when api request fail
     *
     * @param string|null $message
     * @param array $extraData
     * @return JsonResponse
     */
    public function fail(string $message = null, array $extraData = []): JsonResponse
    {
        $response = array_merge($extraData, ['status' => 'fail']);

        if (!is_null($message)) {
            $response['errorMessage'] = $message;
        }

        return response()->json($response, FoundationResponse::HTTP_BAD_REQUEST);
    }

    private function apiRespond(int $statusCode, array $data, array $header = []): JsonResponse
    {
        return Response::json($data, $statusCode, $header);
    }
}
