<?php

namespace Rpungello\RadarSdk\Drivers;

use GuzzleHttp\HandlerStack;

class GuzzleDriver extends \Rpungello\SdkClient\Drivers\GuzzleDriver
{
    use RadarDriver;

    public function __construct(protected string $apiKey, ?HandlerStack $handler = null, protected int $apiVersion = 1)
    {
        parent::__construct("https://api.radar.io/v$this->apiVersion/", $handler, static::getUserAgent());
    }

    protected function getGuzzleClientConfig(): array
    {
        $config = parent::getGuzzleClientConfig();
        $config['headers']['authorization'] = $this->apiKey;

        return $config;
    }
}
