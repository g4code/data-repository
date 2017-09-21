<?php

namespace G4\DataRepository\Exception;

use Exception;
use G4\DataRepository\ErrorCodes;

class MissingIdentityMapKeyException extends Exception
{

    const MESSAGE = 'Missing identity map key';

    public function __construct()
    {
        parent::__construct(self::MESSAGE, ErrorCodes::MISSING_IDENTITY_MAP_KEY_EXCEPTION);
    }
}