<?php

namespace App\Services;

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

    public function present(): array
    {
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

        return response()->json($response, $this->code)->getData(true);
    }
}
