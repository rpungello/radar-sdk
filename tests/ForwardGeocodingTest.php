<?php

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Rpungello\RadarSdk\Dtos\ForwardGeocodeResponse;
use Rpungello\RadarSdk\Exceptions\ForbiddenException;
use Rpungello\RadarSdk\Exceptions\NoResultsException;
use Rpungello\RadarSdk\Exceptions\UnauthorizedException;
use Rpungello\RadarSdk\RadarClient;

it('can geocode addresses', function () {
    $responseData = [
        'meta' => [
            'code' => 200,
        ],
        'addresses' => [
            [
                'addressLabel' => 'Test Address',
                'number' => 123,
                'street' => 'Test Street',
                'city' => 'Test City',
                'state' => 'New Jersey',
                'postalCode' => '12345',
                'county' => 'Test County',
                'countryCode' => 'US',
                'formattedAddress' => '123 Test Street, Test City, NJ 12345 US',
                'layer' => 'address',
                'latitude' => 40.1234,
                'longitude' => -74.1234,
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [
                        -74.1234,
                        40.1234,
                    ],
                ],
                'confidence' => 'exact',
            ],
        ],
    ];

    $mock = new MockHandler([
        new Response(200, ['content-type' => 'application/json'], json_encode($responseData)),
    ]);

    $client = new RadarClient('prj_test_sk_cafebabe', handler: HandlerStack::create($mock));
    $response = $client->forwardGeocode('123 Test Street, Test City, NJ 12345');
    expect($response)->toBeInstanceOf(ForwardGeocodeResponse::class)
        ->and($response->addresses)->toHaveCount(1)
        ->and($response->addresses[0]->addressLabel)->toBe('Test Address')
        ->and($response->addresses[0]->number)->toBe('123')
        ->and($response->addresses[0]->street)->toBe('Test Street')
        ->and($response->addresses[0]->city)->toBe('Test City')
        ->and($response->addresses[0]->state)->toBe('New Jersey')
        ->and($response->addresses[0]->postalCode)->toBe('12345')
        ->and($response->addresses[0]->county)->toBe('Test County')
        ->and($response->addresses[0]->countryCode)->toBe('US')
        ->and($response->addresses[0]->formattedAddress)->toBe('123 Test Street, Test City, NJ 12345 US')
        ->and($response->addresses[0]->latitude)->toBe(40.1234)
        ->and($response->addresses[0]->longitude)->toBe(-74.1234);
});

it('can handle unauthorized errors', function () {
    $mock = new MockHandler([
        new Response(401, ['content-type' => 'application/json'], json_encode(['error' => 'Unauthorized'])),
    ]);

    $client = new RadarClient('prj_test_sk_cafebabe', handler: HandlerStack::create($mock));

    $client->forwardGeocode('123 Test Street, Test City, NJ 12345');
})->throws(UnauthorizedException::class);

it('can handle payment required errors', function () {
    $mock = new MockHandler([
        new Response(402, ['content-type' => 'application/json'], json_encode(['error' => 'Payment Required'])),
    ]);

    $client = new RadarClient('prj_test_sk_cafebabe', handler: HandlerStack::create($mock));

    $client->forwardGeocode('123 Test Street, Test City, NJ 12345');
})->throws(\Rpungello\RadarSdk\Exceptions\PaymentRequiredException::class);

it('can handle forbidden errors', function () {
    $mock = new MockHandler([
        new Response(403, ['content-type' => 'application/json'], json_encode(['error' => 'Forbidden'])),
    ]);

    $client = new RadarClient('prj_test_sk_cafebabe', handler: HandlerStack::create($mock));

    $client->forwardGeocode('123 Test Street, Test City, NJ 12345');
})->throws(ForbiddenException::class);

it('can geocode addresses and return first coordinates', function () {
    $responseData = [
        'meta' => [
            'code' => 200,
        ],
        'addresses' => [
            [
                'addressLabel' => 'Test Address',
                'number' => 123,
                'street' => 'Test Street',
                'city' => 'Test City',
                'state' => 'New Jersey',
                'postalCode' => '12345',
                'county' => 'Test County',
                'countryCode' => 'US',
                'formattedAddress' => '123 Test Street, Test City, NJ 12345 US',
                'layer' => 'address',
                'latitude' => 40.1234,
                'longitude' => -74.1234,
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [
                        -74.1234,
                        40.1234,
                    ],
                ],
                'confidence' => 'exact',
            ],
        ],
    ];

    $mock = new MockHandler([
        new Response(200, ['content-type' => 'application/json'], json_encode($responseData)),
    ]);

    $client = new RadarClient('prj_test_sk_cafebabe', handler: HandlerStack::create($mock));
    $response = $client->forwardGeocode('123 Test Street, Test City, NJ 12345');
    expect($response)->toBeInstanceOf(ForwardGeocodeResponse::class)
        ->and($response->getCoordinatesForFirstResult()->latitude)->toBe(40.1234)
        ->and($response->getCoordinatesForFirstResult()->longitude)->toBe(-74.1234);
});

it('throws exceptions loading first coordinates with no results', function () {
    $responseData = [
        'meta' => [
            'code' => 200,
        ],
        'addresses' => [],
    ];

    $mock = new MockHandler([
        new Response(200, ['content-type' => 'application/json'], json_encode($responseData)),
    ]);

    $client = new RadarClient('prj_test_sk_cafebabe', handler: HandlerStack::create($mock));
    $response = $client->forwardGeocode('123 Test Street, Test City, NJ 12345');
    expect($response)->toBeInstanceOf(ForwardGeocodeResponse::class);
    $response->getCoordinatesForFirstResult();
})->throws(NoResultsException::class);
