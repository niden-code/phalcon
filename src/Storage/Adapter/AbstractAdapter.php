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

namespace Phalcon\Storage\Adapter;

use DateInterval;
use Phalcon\Events\EventsAwareInterface;
use Phalcon\Events\Traits\EventsAwareTrait;
use Phalcon\Storage\Adapter\Traits\ActionsTrait;
use Phalcon\Storage\Serializer\SerializerInterface;
use Phalcon\Storage\SerializerFactory;

use function strtolower;

/**
 * Abstract Storage Adapter
 */
abstract class AbstractAdapter implements AdapterInterface, EventsAwareInterface
{
    use ActionsTrait;
    use EventsAwareTrait;

    /**
     * @var mixed
     */
    protected $adapter;

    /**
     * Name of the default serializer class
     *
     * @var string
     */
    protected string $defaultSerializer = 'php';

    /**
     * EventType prefix.
     *
     * @var string
     */
    protected string $eventType = "storage";

    /**
     * Name of the default TTL (time to live)
     *
     * @var int
     */
    protected int $lifetime = 3600;

    /**
     * @var array
     */
    protected array $options = [];

    /**
     * @var string
     */
    protected string $prefix = 'ph-memo-';

    /**
     * Serializer
     *
     * @var SerializerInterface|null
     */
    protected SerializerInterface | null $serializer;

    /**
     * AbstractAdapter constructor.
     *
     * @param SerializerFactory $serializerFactory
     * @param array             $options
     */
    protected function __construct(
        protected SerializerFactory $serializerFactory,
        array $options = []
    ) {
        /**
         * Lets set some defaults and options here
         */
        $this->defaultSerializer = strtolower(($options['defaultSerializer']) ?? 'php');
        $this->lifetime          = $options['lifetime'] ?? 3600;
        $this->serializer        = $options['serializer'] ?? null;

        if (isset($options['prefix'])) {
            $this->prefix = $options['prefix'];
        }

        unset(
            $options['defaultSerializer'],
            $options['lifetime'],
            $options['serializer'],
            $options['prefix']
        );

        $this->options = $options;
    }

    /**
     * Flushes/clears the cache
     */
    public function clear(): bool
    {
        $result = true;
        $keys   = $this->getKeys();

        foreach ($keys as $key) {
            if (true !== $this->doDelete($this->getPrefixedKey($key))) {
                $result = false;
            }
        }

        return $result;
    }


    /**
     * Decrements a stored number
     *
     * @param string $key
     * @param int    $value
     *
     * @return false|int
     */
    public function decrement(string $key, int $value = 1): false | int
    {
        $this->fireManagerEvent($this->eventType . ":beforeDecrement", $key);

        $result = $this->doDecrement($this->getPrefixedKey($key), $value);

        $this->fireManagerEvent($this->eventType . ":afterDecrement", $key);

        return $result;
    }

    /**
     * Deletes data from the adapter
     *
     * @param string $key
     *
     * @return bool
     */
    public function delete(string $key): bool
    {
        $this->fireManagerEvent($this->eventType . ":beforeDelete", $key);

        $result = $this->doDelete($this->getPrefixedKey($key));

        $this->fireManagerEvent($this->eventType . ":afterDelete", $key);

        return $result;
    }

    /**
     * Reads data from the adapter
     *
     * @param string     $key
     * @param mixed|null $defaultValue
     *
     * @return mixed|null
     */
    public function get(string $key, mixed $defaultValue = null): mixed
    {
        $this->fireManagerEvent($this->eventType . ":beforeGet", $key);

        $result = $this->doGet($this->getPrefixedKey($key), $defaultValue);

        $this->fireManagerEvent($this->eventType . ":afterGet", $key);

        return $result;
    }

    /**
     * Returns the adapter - connects to the storage if not connected
     *
     * @return mixed
     */
    public function getAdapter(): mixed
    {
        return $this->adapter;
    }

    /**
     * Name of the default serializer class
     *
     * @return string
     */
    public function getDefaultSerializer(): string
    {
        return $this->defaultSerializer;
    }

    /**
     * Returns all the keys stored
     *
     * @param string $prefix
     *
     * @return array
     */
    abstract public function getKeys(string $prefix = ''): array;

    /**
     * Returns the lifetime
     *
     * @return int
     */
    public function getLifetime(): int
    {
        return $this->lifetime;
    }

    /**
     * Returns the prefix
     *
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * Get the serializer
     *
     * @return SerializerInterface
     */
    public function getSerializer(): SerializerInterface
    {
        return $this->serializer;
    }

    /**
     * Checks if an element exists in the cache
     *
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        $this->fireManagerEvent($this->eventType . ":beforeHas", $key);

        $result = $this->doHas($this->getPrefixedKey($key));

        $this->fireManagerEvent($this->eventType . ":afterHas", $key);

        return $result;
    }

    /**
     * Increments a stored number
     *
     * @param string $key
     * @param int    $value
     *
     * @return false|int
     */
    public function increment(string $key, int $value = 1): false | int
    {
        $this->fireManagerEvent($this->eventType . ":beforeIncrement", $key);

        $result = $this->doIncrement($this->getPrefixedKey($key), $value);

        $this->fireManagerEvent($this->eventType . ":afterIncrement", $key);

        return $result;
    }

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
    public function set(string $key, mixed $value, mixed $ttl = null): bool
    {
        $this->fireManagerEvent($this->eventType . ":beforeSet", $key);

        $result = $this->doSet($this->getPrefixedKey($key), $value, $ttl);

        $this->fireManagerEvent($this->eventType . ":afterSet", $key);

        return $result;
    }

    /**
     * @param string $serializer
     */
    public function setDefaultSerializer(string $serializer): void
    {
        $this->defaultSerializer = strtolower($serializer);
    }
}
