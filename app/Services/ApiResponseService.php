<?php

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class ApiResponseService
{
    private $data;

    private $message;

    private $code = 200;

    public function setData($data): self
    {
        $this->data = $data;
        return $this;
    }

    public function setMessage($message): self
    {
        $this->message = $message;
        return $this;
    }

    public function setCode($code): self
    {
        $this->code = $code;
        return $this;
    }

    public function present()
    {
        if ($this->data instanceof Paginator || isset($this->data->resource) && $this->data?->resource instanceof Paginator) {
            $this->setPaginator($this->data);
        } else if ($this->data instanceof LengthAwarePaginator || isset($this->data->resource) && $this->data?->resource instanceof LengthAwarePaginator) {
            $this->setLengthAwarePaginator($this->data);
        }

        $response = [
            'success' => true,
            'data' => $this->data,
            'message' => $this->message
        ];

        if ($this->code != 200) {
            $response['success'] = false;
        }

        if ($this->message == "") {
            unset($response['message']);
        }

        if (!$this->data) {
            unset($response['data']);
        }

        return response()->json($response, $this->code);
    }

    private function setPaginator($data): self
    {
        $resource = isset($this->data->resource) ? $this->data->resource : $this->data;

        $data = [
            'data' => $resource->items(),
            'links' => [
                'first' => $resource->url(1),
                'prev' => $resource->previousPageUrl(),
                'next' => $resource->nextPageUrl(),
            ],
            'meta' => [
                'current_page' => $resource->currentPage(),
                'from' => $resource->firstItem(),
                'path' => $resource->resolveCurrentPath(),
                'per_page' => $resource->perPage(),
                'to' => $resource->lastItem(),
            ],
        ];
        $this->data = $data;

        return $this;
    }

    private function setLengthAwarePaginator(): self
    {
        $resource = isset($this->data->resource) ? $this->data->resource : $this->data;

        $data = [
            'data' => $resource->items(),
            'links' => [
                'first' => $resource->url(1),
                'last' => $resource->url($resource->lastPage()),
                'prev' => $resource->previousPageUrl(),
                'next' => $resource->nextPageUrl(),
            ],
            'meta' => [
                'current_page' => $resource->currentPage(),
                'from' => $resource->firstItem(),
                'last_page' => $resource->lastPage(),
                'path' => $resource->resolveCurrentPath(),
                'per_page' => $resource->perPage(),
                'to' => $resource->lastItem(),
                'total' => $resource->total(),
            ],
        ];
        $this->data = $data;

        return $this;
    }
}
