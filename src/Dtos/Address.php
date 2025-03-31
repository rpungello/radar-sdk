<?php

namespace Rpungello\RadarSdk\Dtos;

use Rpungello\SdkClient\DataTransferObject;

class Address extends DataTransferObject
{
    public string $addressLabel;

    public ?string $number = null;

    public ?string $street = null;

    public ?string $city = null;

    public ?string $state = null;

    public ?string $postalCode = null;

    public ?string $county = null;

    public ?string $countryCode = null;

    public ?string $formattedAddress = null;

    public ?string $layer = null;

    public ?string $latitude = null;

    public ?string $longitude = null;
}
