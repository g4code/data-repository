<?php

namespace G4\DataRepository\Exception;

use Exception;
use G4\DataRepository\ErrorCodes;

class MissingStorageException extends Exception
{

    const MESSAGE = 'Expected at least one storage, none given.';

    public function __construct()
    {
        parent::__construct(self::MESSAGE, ErrorCodes::MISSING_STORAGE_INSTANCE_EXCEPTION);
    }
}
