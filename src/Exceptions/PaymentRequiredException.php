<?php

namespace Rpungello\RadarSdk\Exceptions;

use Throwable;

class PaymentRequiredException extends ClientException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('Radar organization disabled due to lack of payment', 402, $previous);
    }
}
