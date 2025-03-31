<?php

namespace Rpungello\RadarSdk\Dtos;

use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Casters\ArrayCaster;

class ForwardGeocodeResponse extends ResponseDto
{
    #[CastWith(ArrayCaster::class, itemType: Address::class)]
    public array $addresses = [];
}
