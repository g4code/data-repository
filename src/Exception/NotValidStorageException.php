<?php

namespace G4\DataRepository\Exception;

use Exception;
use G4\DataRepository\ErrorCodes;

class NotValidStorageException extends Exception
{

    final public const MESSAGE = 'Not valid storage instance provided.';

    public function __construct()
    {
        parent::__construct(self::MESSAGE, ErrorCodes::NOT_VALID_STORAGE_EXCEPTION);
    }
}
