<?php

namespace Rpungello\RadarSdk\Dtos;

use Rpungello\SdkClient\DataTransferObject;

abstract class ResponseDto extends DataTransferObject
{
    public Meta $meta;
}
