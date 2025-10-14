<?php

namespace Rpungello\RadarSdk\Drivers;

use Composer\InstalledVersions;

trait RadarDriver
{
    protected static function getUserAgent(): string
    {
        return 'rpungello/radar-sdk/'.static::getVersion();
    }

    protected static function getVersion(): string
    {
        return InstalledVersions::getVersion('rpungello/radar-sdk');
    }
}
