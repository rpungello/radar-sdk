<?php

namespace Rpungello\RadarSdk\Dtos;

use Rpungello\SdkClient\DataTransferObject;

class Meta extends DataTransferObject
{
    public int $code = 200;

    public bool $hasMore = false;
}
