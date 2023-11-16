<?php

namespace G4\DataRepository\Exception;

use Exception;
use G4\DataRepository\ErrorCodes;

class MissingCustomCommandException extends Exception
{

    final public const MESSAGE = 'Missing custom command.';

    public function __construct()
    {
        parent::__construct(self::MESSAGE, ErrorCodes::MISSING_CUSTOM_COMMAND_EXCEPTION);
    }
}
