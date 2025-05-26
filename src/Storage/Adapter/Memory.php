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
use Exception as BaseException;
use Phalcon\Storage\SerializerFactory;

use function array_key_exists;
use function array_keys;
use function is_int;

/**
 * Memory adapter
 *
 * @property array $data
 * @property array $options
 */
class Memory extends AbstractAdapter
{
    /**
     * @var array
     */
    protected array $data = [];

    /**
     * Memory constructor.
     *
     * @param SerializerFactory $factory
     * @param array             $options
     *
     * @throws BaseException
     */
    public function __construct(
        SerializerFactory $factory,
        array $options = []
    ) {
        parent::__construct($factory, $options);

        $this->initSerializer();
    }

    /**
     * Stores data in the adapter
     *
     * @param string $prefix
     *
     * @return array
     */
    public function getKeys(string $prefix = ''): array
    {
        return $this->getFilteredKeys(array_keys($this->data), $prefix);
    }

    /**
     * Stores data in the adapter forever. The key needs to manually deleted
     * from the adapter.
     *
     * @param string $key
     * @param mixed  $data
     *
     * @return bool
     */
    public function setForever(string $key, mixed $data): bool
    {
        return $this->doSet($this->getPrefixedKey($key), $data);
    }

    /**
     * Decrements a stored number
     *
     * @param string $key
     * @param int    $value
     *
     * @return false|int
     */
    protected function doDecrement(string $key, int $value = 1): false | int
    {
        if (array_key_exists($key, $this->data)) {
            $this->data[$key] -= $value;

            return $this->data[$key];
        }

        return false;
    }

    /**
     * Deletes data from the adapter
     *
     * @param string $key
     *
     * @return bool
     */
    protected function doDelete(string $key): bool
    {
        $exists = array_key_exists($key, $this->data);

        unset($this->data[$key]);

        return $exists;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    protected function doGetData(string $key): mixed
    {
        return $this->data[$key];
    }

    /**
     * Checks if an element exists in the cache
     *
     * @param string $key
     *
     * @return bool
     */
    protected function doHas(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * Increments a stored number
     *
     * @param string $key
     * @param int    $value
     *
     * @return false|int
     */
    protected function doIncrement(string $key, int $value = 1): false | int
    {
        if (array_key_exists($key, $this->data)) {
            $this->data[$key] += $value;

            return $this->data[$key];
        }

        return false;
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
    protected function doSet(string $key, mixed $value, mixed $ttl = null): bool
    {
        if (is_int($ttl) && $ttl < 1) {
            return $this->doDelete($key);
        }

        $this->data[$key] = $this->getSerializedData($value);

        return true;
    }
}
