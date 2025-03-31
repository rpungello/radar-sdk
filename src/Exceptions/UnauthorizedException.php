<?php

namespace Rpungello\RadarSdk\Exceptions;

use Rpungello\RadarSdk\Exceptions\ApiException;
use Throwable;

class UnauthorizedException extends ApiException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('You must provide a valid radar.com API key', 401, $previous);
    }
}
