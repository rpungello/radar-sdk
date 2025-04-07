<?php

namespace Rpungello\RadarSdk;

use Composer\InstalledVersions;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use Rpungello\RadarSdk\Dtos\Coordinate;
use Rpungello\RadarSdk\Dtos\ForwardGeocodeResponse;
use Rpungello\RadarSdk\Dtos\ReverseGeocodeResponse;
use Rpungello\RadarSdk\Exceptions\ApiException;
use Rpungello\RadarSdk\Exceptions\ForbiddenException;
use Rpungello\RadarSdk\Exceptions\PaymentRequiredException;
use Rpungello\RadarSdk\Exceptions\UnauthorizedException;
use Rpungello\SdkClient\SdkClient;
use RuntimeException;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class RadarClient extends SdkClient
{
    public function __construct(protected string $apiKey, ?HandlerStack $handler = null, protected int $apiVersion = 1)
    {
        parent::__construct("https://api.radar.io/v$this->apiVersion/", $handler, static::getUserAgent());
    }

    /**
     * @throws ApiException
     * @throws RuntimeException
     */
    public function forwardGeocode(string $query, array $layers = [], ?string $country = null, ?string $lang = null): ForwardGeocodeResponse
    {
        $queryParams = [
            'query' => $query,
        ];

        if (! empty($layers)) {
            $queryParams['layers'] = implode(',', $layers);
        }

        if (! empty($country)) {
            $queryParams['country'] = $country;
        }

        if (! empty($lang)) {
            $queryParams['lang'] = $lang;
        }

        try {
            return $this->getDto('geocode/forward', ForwardGeocodeResponse::class, $queryParams);
        } catch (BadResponseException $e) {
            throw $this->parseBadResponseException($e);
        } catch (GuzzleException|UnknownProperties) {
            throw new RuntimeException('Error while fetching forward geocode data');
        }
    }

    public function reverseGeocode(Coordinate $coordinate, array $layers = []): ReverseGeocodeResponse
    {
        $queryParams = [
            'coordinates' => "$coordinate->latitude,$coordinate->longitude",
        ];

        try {
            return $this->getDto('geocode/reverse', ReverseGeocodeResponse::class, $queryParams);
        } catch (BadResponseException $e) {
            throw $this->parseBadResponseException($e);
        } catch (GuzzleException|UnknownProperties) {
            throw new RuntimeException('Error while fetching reverse geocode data');
        }
    }

    protected function parseBadResponseException(BadResponseException $e): ApiException
    {
        if ($e->getCode() === 401) {
            return new UnauthorizedException($e);
        } elseif ($e->getCode() === 402) {
            return new PaymentRequiredException($e);
        } elseif ($e->getCode() === 403) {
            return new ForbiddenException($e);
        } else {
            return new ApiException($e->getMessage(), $e->getCode(), $e);
        }
    }

    protected function getGuzzleClientConfig(): array
    {
        $config = parent::getGuzzleClientConfig();
        $config['headers']['authorization'] = $this->apiKey;

        return $config;
    }

    private static function getUserAgent(): string
    {
        return 'rpungello/radar-sdk/'.static::getVersion();
    }

    private static function getVersion(): string
    {
        return InstalledVersions::getVersion('rpungello/radar-sdk');
    }
}
