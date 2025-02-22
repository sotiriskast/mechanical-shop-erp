<?php

namespace Modules\Customer\src\Exceptions;

use Exception;

class CustomerException extends Exception
{
    protected $data;

    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null, array $data = [])
    {
        parent::__construct($message, $code, $previous);
        $this->data = $data;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
