<?php

namespace G4\DataRepository\Exception;

use Exception;
use G4\DataRepository\ErrorCodes;

class MissingDatasetNameValueException extends Exception
{
    const MESSAGE = 'Missing dataset name(table name) value.';

    public function __construct()
    {
        parent::__construct(self::MESSAGE, ErrorCodes::MISSING_DATASET_NAME_VALUE_EXCEPTION);
    }
}
