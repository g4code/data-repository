<?php

namespace G4\DataRepository\Exception;

use Exception;
use G4\DataRepository\ErrorCodes;

class MissingIdentityException extends Exception
{

    const MESSAGE = 'Expected Identity  \G4\DataMapper\Common\IdentityInterface.';

    public function __construct()
    {
        parent::__construct(self::MESSAGE, ErrorCodes::MISSING_IDENTITY_EXCEPTION);
    }
}
