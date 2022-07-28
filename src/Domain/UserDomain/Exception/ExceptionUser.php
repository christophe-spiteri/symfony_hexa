<?php

namespace Domain\UserDomain\Exception;

use RuntimeException;

class ExceptionUser extends RuntimeException
{

    /**
     * @param string $string
     */
    public function __construct(private string $field, string $message)
    {
        parent::__construct();
        $this->message = $message;
    }

    public function getField()
    {
        return $this->field;
    }
}