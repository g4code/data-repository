<?php

namespace G4\DataRepository\Exception;

use Exception;
use G4\DataRepository\ErrorCodes;

class MissingActionException extends Exception
{

    const MESSAGE = 'Expected action (insert, upsert, update or delete).';

    public function __construct()
    {
        parent::__construct(self::MESSAGE, ErrorCodes::MISSING_ACTION_EXCEPTION);
    }
}