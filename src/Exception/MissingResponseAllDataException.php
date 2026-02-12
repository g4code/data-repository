<?php

namespace G4\DataRepository\Exception;

use Exception;
use G4\DataRepository\ErrorCodes;

class MissingResponseAllDataException extends Exception
{

    final public const MESSAGE = 'Missing response all data';

    public function __construct()
    {
        parent::__construct(self::MESSAGE, ErrorCodes::MISSING_RESPONSE_ALL_DATA);
    }
}
