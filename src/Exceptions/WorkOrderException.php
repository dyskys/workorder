<?php

namespace dww\workorder\Exceptions;

use Exception;

class WorkOrderException extends Exception
{

    /**
     *
     * WorkOrderException constructor.
     * @param int $code
     * @param string $message
     */
    public function __construct(int $code, string $message)
    {
        parent::__construct($message, $code);
    }
}
