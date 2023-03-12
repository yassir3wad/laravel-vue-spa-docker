<?php

namespace App\Utils;

use App\Exceptions\ResponderException;
use Illuminate\Support\Arr;

class Responder
{
    private $status;

    private $status_code;

    private $locale;

    private $data;

    private $errors;

    private $message;

    private $others;

    public function __construct()
    {
        $this->status = 'ok';
        $this->status_code = 200;
        $this->locale = app()->getLocale();
        $this->data = null;
        $this->errors = null;
        $this->message = null;
        $this->others = [];
    }

    public function setOthers($data = [])
    {
        $this->others = $data;

        return $this;
    }

    public function setErrors($errors)
    {
        $this->errors = $errors;

        return $this;
    }

    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    public function asError()
    {
        return $this->setErrors('error');
    }

    public function setStatusCode($status_code)
    {
        $this->status_code = $status_code;

        return $this;
    }

    public function getStatusCode()
    {
        return $this->status_code;
    }

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    public function setMessage($message, $replace = [])
    {
        $this->message = trans($message, $replace);

        return $this;
    }

    public function addData($name, $value)
    {
        $this->data[$name] = $value;

        return $this;
    }

    public function setPaginatedData($data)
    {
        $data = (array) $data->response(request())->getData();

        $this->data = Arr::get($data, 'data');
        $this->others['meta'] = Arr::except((array) $data['meta'], ['links']);

        return $this;
    }

    public function makeApiError($message = '', $replace = [], $data = [], $status_code = 200)
    {
        return $this->setStatus('ng')
            ->setMessage($message, $replace)
            ->setData($data)
            ->setStatusCode($status_code);
    }

    public function throw($message, $statusCode = 400)
    {
        $this->setMessage($message)->setStatusCode($statusCode)->setStatus('ng');

        throw new ResponderException($this);
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function respond()
    {
        return response(array_merge([
            'status' => $this->status,
            'status_code' => $this->status_code,
            'locale' => $this->locale,
            'message' => $this->message,
            'data' => $this->data,
            'errors' => $this->errors,
        ], $this->others), $this->status_code);
    }
}
