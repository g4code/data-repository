<?php

namespace G4\DataRepository\Exception;

use Exception;
use G4\DataRepository\ErrorCodes;

class MissingResponseOneDataException extends Exception
{

    const MESSAGE = 'Missing response one data';

    public function __construct()
    {
        parent::__construct(self::MESSAGE, ErrorCodes::MISSING_RESPONSE_ONE_DATA);
    }
}
