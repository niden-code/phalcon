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

namespace Phalcon\Storage\Adapter\Traits;

use DateInterval;
use DateTime;
use Exception;
use Phalcon\Storage\Serializer\SerializerInterface;
use Phalcon\Storage\SerializerFactory;

use function is_object;

/**
 * Actions trait for the storage adapter
 *
 * @property mixed               $adapter
 * @property string              $defaultSerializer
 * @property int                 $lifetime
 * @property array               $options
 * @property string              $prefix
 * @property SerializerInterface $serializer
 * @property SerializerFactory   $serializerFactory
 */
trait ActionsTrait
{
    /**
     * Flushes/clears the cache
     *
     * @return bool
     */
    abstract public function clear(): bool;

    /**
     * Decrements a stored number
     *
     * @param string $key
     * @param int    $value
     *
     * @return false|int
     */
    abstract protected function doDecrement(
        string $key,
        int $value = 1
    ): false | int;

    /**
     * Deletes data from the adapter
     *
     * @param string $key
     *
     * @return bool
     */
    abstract protected function doDelete(string $key): bool;

    /**
     * @param string $key
     *
     * @return mixed
     */
    protected function doGet(string $key, mixed $defaultValue = null): mixed
    {
        if (true !== $this->doHas($key)) {
            return $defaultValue;
        }

        $content = $this->doGetData($key);

        return $this->getUnserializedData($content, $defaultValue);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    protected function doGetData(string $key): mixed
    {
        return $this->adapter->get($key);
    }

    /**
     * Checks if an element exists in the cache
     *
     * @param string $key
     *
     * @return bool
     */
    abstract protected function doHas(string $key): bool;

    /**
     * Increments a stored number
     *
     * @param string $key
     * @param int    $value
     *
     * @return false|int
     */
    abstract protected function doIncrement(string $key, int $value = 1): false | int;

    /**
     * Stores data in the adapter. If the TTL is `null` (default) or not defined
     * then the default TTL will be used, as set in this adapter. If the TTL
     * is `0` or a negative number, a `delete()` will be issued, since this
     * item has expired. If you need to set this key forever, you should use
     * the `setForever()` method.
     *
     * @param string                $key
     * @param mixed                 $value
     * @param DateInterval|int|null $ttl
     *
     * @return bool
     */
    abstract protected function doSet(
        string $key,
        mixed $value,
        DateInterval | int | null $ttl = null
    ): bool;

    /**
     * Filters the keys array based on global and passed prefix
     *
     * @param array  $keys
     * @param string $prefix
     *
     * @return array
     */
    protected function getFilteredKeys(array $keys, string $prefix): array
    {
        return array_values(
            array_map(
                fn($key) => str_replace($this->prefix, '', $key),
                array_filter(
                    $keys,
                    fn($key) => str_starts_with($key, $this->prefix . $prefix)
                )
            )
        );
    }

    /**
     * Returns the key requested, prefixed
     *
     * @param string $key
     *
     * @return string
     */
    protected function getPrefixedKey(mixed $key): string
    {
        return $this->prefix . ((string)$key);
    }

    /**
     * Returns serialized data
     *
     * @param mixed $content
     *
     * @return mixed|string|null
     * @throws Exception
     */
    protected function getSerializedData(mixed $content): mixed
    {
        if (null !== $this->serializer) {
            $this->serializer->setData($content);
            $content = $this->serializer->serialize();
        }

        return $content;
    }

    /**
     * Calculates the TTL for a cache item
     *
     * @param DateInterval|int|null $ttl
     *
     * @return int
     * @throws Exception
     */
    protected function getTtl(DateInterval | int | null $ttl): int
    {
        if (null === $ttl) {
            return $this->lifetime;
        }

        if (is_object($ttl) && $ttl instanceof DateInterval) {
            $dateTime = new DateTime('@0');

            return $dateTime->add($ttl)->getTimestamp();
        }

        return (int)$ttl;
    }

    /**
     * Returns unserialized data
     *
     * @param mixed      $content
     * @param mixed|null $defaultValue
     *
     * @return mixed
     */
    protected function getUnserializedData(
        mixed $content,
        mixed $defaultValue = null
    ): mixed {
        if (null !== $this->serializer) {
            $this->serializer->unserialize($content);

            if (true !== $this->serializer->isSuccess()) {
                return $defaultValue;
            }

            $content = $this->serializer->getData();
        }

        return $content;
    }

    /**
     * Initializes the serializer
     *
     * @return void
     * @throws Exception
     */
    protected function initSerializer(): void
    {
        if (
            !empty($this->defaultSerializer) &&
            !is_object($this->serializer)
        ) {
            $className        = $this->defaultSerializer;
            $this->serializer = $this->serializerFactory->newInstance($className);
        }
    }
}
