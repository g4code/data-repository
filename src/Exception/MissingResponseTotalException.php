<?php

namespace G4\DataRepository\Exception;

use Exception;
use G4\DataRepository\ErrorCodes;

class MissingResponseTotalException extends Exception
{

    const MESSAGE = 'Missing response total';

    public function __construct()
    {
        parent::__construct(self::MESSAGE, ErrorCodes::MISSING_RESPONSE_TOTAL);
    }
}
