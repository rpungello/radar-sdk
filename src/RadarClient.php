<?php

namespace Rpungello\RadarSdk;

use Rpungello\RadarSdk\Dtos\Coordinate;
use Rpungello\RadarSdk\Dtos\ForwardGeocodeResponse;
use Rpungello\RadarSdk\Dtos\ReverseGeocodeResponse;
use Rpungello\RadarSdk\Exceptions\ApiException;
use Rpungello\RadarSdk\Exceptions\ForbiddenException;
use Rpungello\RadarSdk\Exceptions\PaymentRequiredException;
use Rpungello\RadarSdk\Exceptions\UnauthorizedException;
use Rpungello\SdkClient\Exceptions\RequestException;
use Rpungello\SdkClient\SdkClient;
use RuntimeException;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class RadarClient extends SdkClient
{
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
        } catch (RequestException $e) {
            throw $this->parseRequestException($e);
        } catch (UnknownProperties) {
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
        } catch (RequestException $e) {
            throw $this->parseRequestException($e);
        } catch (UnknownProperties) {
            throw new RuntimeException('Error while fetching reverse geocode data');
        }
    }

    protected function parseRequestException(RequestException $e): ApiException
    {
        if ($e->getHttpStatusCode() === 401) {
            return new UnauthorizedException($e);
        } elseif ($e->getHttpStatusCode() === 402) {
            return new PaymentRequiredException($e);
        } elseif ($e->getHttpStatusCode() === 403) {
            return new ForbiddenException($e);
        } else {
            return new ApiException($e->getMessage(), $e->getHttpStatusCode(), $e);
        }
    }
}
