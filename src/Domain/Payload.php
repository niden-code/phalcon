<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * (c) Phalcon Team <team@phalcon.io>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Phalcon\Domain;

use PayloadInterop\DomainPayload;

class Payload implements DomainPayload
{
    /**
     * Payload constructor.
     *
     * @param string                  $status
     * @param array<array-key, mixed> $result
     */
    public function __construct(
        protected string $status,
        protected array $result = []
    ) {
    }

    /**
     * @return array<array-key, mixed>
     */
    public function getResult(): array
    {
        return $this->result;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }
}
