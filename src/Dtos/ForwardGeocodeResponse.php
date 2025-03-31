<?php

namespace Rpungello\RadarSdk\Dtos;

use Rpungello\RadarSdk\Dtos\ResponseDto;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Casters\ArrayCaster;

class ForwardGeocodeResponse extends ResponseDto
{
    #[CastWith(ArrayCaster::class, itemType: Address::class)]
    public array $addresses = [];
}
