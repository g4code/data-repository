<?php

namespace G4\DataRepository\Exception;

use Exception;
use G4\DataRepository\ErrorCodes;

class NotFoundException extends Exception
{

    const MESSAGE = 'Not found';

    public function __construct()
    {
        parent::__construct(self::MESSAGE, 404); // TODO - move to const
    }
}