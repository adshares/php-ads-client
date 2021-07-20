<?php

/**
 * Copyright (c) 2018-2021 Adshares sp. z o.o.
 *
 * This file is part of ADS PHP Client
 *
 * ADS PHP Client is free software: you can redistribute and/or modify it
 * under the terms of the GNU General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * ADS PHP Client is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty
 * of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with ADS PHP Client. If not, see <https://www.gnu.org/licenses/>
 */

namespace Adshares\Ads\Tests\E2E;

use Adshares\Ads\AdsClient;
use Adshares\Ads\Driver\CliDriver;
use Psr\Log\LoggerInterface;

class TestAdsClient extends AdsClient
{
    private $address;

    public function __construct(LoggerInterface $logger = null)
    {
        $this->address = getenv('ADS_ADDRESS');
        $driver = new CliDriver(
            $this->address,
            getenv('ADS_SECRET'),
            getenv('ADS_HOST'),
            getenv('ADS_PORT'),
            $logger
        );
        parent::__construct($driver, $logger);
    }

    public function getAddress(): string
    {
        return $this->address;
    }
}
