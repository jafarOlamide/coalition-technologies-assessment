<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ApiController extends Controller
{
    protected $statusCode = Response::HTTP_OK;

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): ApiController
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function getArrayValues($data): array
    {
        $array = [];

        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $array[] = [
                    'name'  => $key,
                    'value' => $value,
                ];
            }
        } else {
            $arry = $data;
        }

        return $array;
    }

    /**
     * @param null|array|\Illuminate\Database\Eloquent\Model $data
     * @param string                                         $status
     * @param array                                          $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respond($data, string $status = 'success', array $headers = []): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => $status,
            'data'   => $data,
        ], $this->getStatusCode(), $headers);
    }

    public function respondWithError(string $message, array $headers = []): \Illuminate\Http\JsonResponse
    {
        return response()->json(['status' => 'fail',
            'status_code' => $this->getStatusCode(),
            'error'       => [
                'message' => $message,
            ],
        ], $this->getStatusCode(), $headers);
    }

    public function respondNotFound(string $message = 'The requested resource does not exist.'): \Illuminate\Http\JsonResponse
    {
        return $this->setStatusCode(Response::HTTP_NOT_FOUND)->respondWithError($message);
    }

    public function respondUnprocessed(string $message): \Illuminate\Http\JsonResponse
    {
        return $this->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)->respondWithError($message);
    }

    public function respondOk(string $message, string $status = 'success'): \Illuminate\Http\JsonResponse
    {
        return $this->respond(['message' => $message], $status);
    }

    public function respondCreated(array $data): \Illuminate\Http\JsonResponse
    {
        return $this->setStatusCode(Response::HTTP_CREATED)->respond($data);
    }

    public function respondNoContent(): \Illuminate\Http\JsonResponse
    {
        $this->setStatusCode(Response::HTTP_NO_CONTENT);

        return response()->json([], $this->getStatusCode());
    }

}
