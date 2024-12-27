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

namespace Phalcon\Assets;

use function hash;

/**
 * Represents an inline asset
 */
class Inline implements AssetInterface
{
    /**
     * Inline constructor.
     *
     * @param string                $type
     * @param string                $content
     * @param bool                  $filter
     * @param array<string, string> $attributes
     */
    public function __construct(
        protected string $type,
        protected string $content,
        protected bool $filter = true,
        protected array $attributes = []
    ) {
    }

    /**
     * Gets the asset's key.
     *
     * @return string
     */
    public function getAssetKey(): string
    {
        $key = $this->getType() . ':' . $this->getContent();

        return hash("sha256", $key);
    }

    /**
     * Gets extra HTML attributes.
     *
     * @return array<string, string>
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Gets if the asset content
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Gets if the asset must be filtered or not.
     *
     * @return bool
     */
    public function getFilter(): bool
    {
        return $this->filter;
    }

    /**
     * Gets the asset's type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Sets extra HTML attributes
     *
     * @param array<string, string> $attributes
     *
     * @return AssetInterface
     */
    public function setAttributes(array $attributes): AssetInterface
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Sets if the asset must be filtered or not
     *
     * @param bool $filter
     *
     * @return AssetInterface
     */
    public function setFilter(bool $filter): AssetInterface
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * Sets the inline's type
     *
     * @param string $type
     *
     * @return AssetInterface
     */
    public function setType(string $type): AssetInterface
    {
        $this->type = $type;

        return $this;
    }
}
