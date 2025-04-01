<?php

namespace Rpungello\RadarSdk\Exceptions;

use Throwable;

class ForbiddenException extends ClientException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('The API key provided does not have access to the requested resource', 403, $previous);
    }
}
