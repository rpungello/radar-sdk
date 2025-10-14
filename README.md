# Radar SDK

[![Latest Version on Packagist](https://img.shields.io/packagist/v/rpungello/radar-sdk.svg?style=flat-square)](https://packagist.org/packages/rpungello/radar-sdk)
[![Tests](https://img.shields.io/github/actions/workflow/status/rpungello/radar-sdk/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/rpungello/radar-sdk/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/rpungello/radar-sdk.svg?style=flat-square)](https://packagist.org/packages/rpungello/radar-sdk)

PHP SDK for interfacing with radar.com's API. Currently only forward geocoding is supported, but more of the API will be added in the future.

## Installation

You can install the package via composer:

```bash
composer require rpungello/radar-sdk
```

## Usage

### Initialize Client

```php
$client = new \Rpungello\RadarSdk\RadarClient(
    new \Rpungello\RadarSdk\Drivers\GuzzleDriver('your_secret_key_here')
);
```

### Forward Geocoding

```php
$client = new \Rpungello\RadarSdk\RadarClient(
    new \Rpungello\RadarSdk\Drivers\GuzzleDriver('your_secret_key_here')
);
$response = $client->forwardGeocode('Full Address Here');
````

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Rob Pungello](https://github.com/rpungello)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
