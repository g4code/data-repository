<?php

namespace G4\DataRepository\Exception;

use Exception;
use G4\DataRepository\ErrorCodes;

class MissingResponseCountException extends Exception
{

    const MESSAGE = 'Missing response count';

    public function __construct()
    {
        parent::__construct(self::MESSAGE, ErrorCodes::MISSING_RESPONSE_COUNT);
    }
}
