<?php

namespace Domain\ValidationException;

use Exception;

class WebsiteValidationException extends Exception
{
    public function __construct($message = 'Validation error', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
