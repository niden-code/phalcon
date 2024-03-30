<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Phalcon\Tests1\Fixtures\Http\Message;

use Phalcon\Http\Message\Factories\ServerRequestFactory;

class ServerRequestFactoryFixture extends ServerRequestFactory
{
    /**
     * Returns the apache_request_headers if it exists
     *
     * @return array|bool
     */
    protected function getHeaders()
    {
        return [
            'Accept-Language' => 'en-us',
            'Accept-Encoding' => 'gzip, deflate',
            'Host'            => 'dev.phalcon.ld',
            'Authorization'   => 'Bearer',
        ];
    }
}
