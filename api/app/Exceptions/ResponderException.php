<?php

namespace App\Exceptions;

use App\Helpers\Responder;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ResponderException extends HttpException
{
    private $responder;

    public function __construct(Responder $responder)
    {
        $this->responder = $responder;
        parent::__construct($responder->getStatusCode());
    }

    public function respond()
    {
        return $this->responder->respond();
    }
}
