<?php

namespace G4\DataRepository\Exception;

use Exception;
use G4\DataRepository\ErrorCodes;

class MissingRussianDollKeyException extends Exception
{

    const MESSAGE = 'Missing russian doll key';

    public function __construct()
    {
        parent::__construct(self::MESSAGE, ErrorCodes::MISSING_RUSSIAN_DOLL_EXCEPTION);
    }
}