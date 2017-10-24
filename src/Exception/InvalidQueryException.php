<?php

namespace G4\DataRepository\Exception;

use Exception;
use G4\DataRepository\ErrorCodes;

class InvalidQueryException extends Exception
{

    const MESSAGE = 'Invalid query';

    public function __construct()
    {
        parent::__construct(self::MESSAGE, ErrorCodes::INVALID_QUERY_EXCEPTION);
    }
}