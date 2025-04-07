<?php

namespace Rpungello\RadarSdk\Dtos;

use Rpungello\RadarSdk\Exceptions\NoResultsException;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Casters\ArrayCaster;

class ForwardGeocodeResponse extends ResponseDto
{
    #[CastWith(ArrayCaster::class, itemType: Address::class)]
    public array $addresses = [];

    /**
     * @throws NoResultsException
     */
    public function getCoordinatesForFirstResult(): Coordinate
    {
        if (empty($this->addresses)) {
            throw new NoResultsException('No addresses found in the response');
        }

        return new Coordinate(
            $this->addresses[0]->latitude,
            $this->addresses[0]->longitude,
        );
    }
}
