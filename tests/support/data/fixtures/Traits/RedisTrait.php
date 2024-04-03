<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * (c) Phalcon Team <team@phalcon.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Phalcon\Tests1\Fixtures\Traits;

use Exception;
use Predis\Client as RedisDriver;
use RuntimeException;
use SebastianBergmann\Comparator\ComparisonFailure;
use SebastianBergmann\Comparator\Factory as ComparatorFactory;

use function array_slice;
use function call_user_func_array;
use function func_get_args;
use function in_array;
use function is_array;
use function is_bool;
use function is_null;
use function is_scalar;
use function sprintf;
use function strpos;
use function strtolower;
use function var_export;

trait RedisTrait
{
    /**
     * The Redis driver
     */
    public ?RedisDriver $redis = null;

    /**
     * Code to run before each test.
     *
     * @return void
     */
    public function redisSetup(): void
    {
        try {
            $this->redis = new RedisDriver(self::getOptionsRedis());
        } catch (Exception $exception) {
            throw new RuntimeException($exception->getMessage());
        }

        $this->cleanup();
    }

    /**
     * Code to run after each test.
     *
     * @return void
     */
    public function redisTearDown(): void
    {
        if (empty($this->redis)) {
            return;
        }

        $this->cleanup();
        $this->redis->quit();
    }

    /**
     * Delete all the keys in the Redis database
     *
     * @throws RuntimeException
     */
    public function cleanup(): void
    {
        try {
            $this->redis->flushdb();
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Returns the value of a given key
     *
     * @param string $key
     */
    public function redisGet(string $key): array|string|null
    {
        $args = func_get_args();

        switch ($this->redis->type($key)) {
            case 'none':
                throw new RuntimeException(
                    sprintf('Cannot get key "%s" as it does not exist', $key)
                );

            case 'string':
                $reply = $this->redis->get($key);
                break;

            case 'list':
                if (count($args) === 2) {
                    $reply = $this->redis->lindex($key, $args[1]);
                } else {
                    $reply = $this->redis->lrange(
                        $key,
                        $args[1] ?? 0,
                        $args[2] ?? -1
                    );
                }

                break;

            case 'set':
                $reply = $this->redis->smembers($key);
                break;

            case 'zset':
                if (count($args) === 2) {
                    throw new RuntimeException(
                        'The method redis(), when used with sorted sets, '
                        . 'expects either one argument or three'
                    );
                }

                $reply = $this->redis->zrange(
                    $key,
                    isset($args[2]) ? $args[1] : 0,
                    $args[2] ?? -1,
                    'WITHSCORES'
                );
                break;

            case 'hash':
                $reply = isset($args[1])
                    ? $this->redis->hget($key, $args[1])
                    : $this->redis->hgetall($key);
                break;

            default:
                $reply = null;
        }

        return $reply;
    }

    /**
     * Creates or modifies keys
     *
     * If $key already exists:
     *
     * - Strings: its value will be overwritten with $value
     * - Other types: $value items will be appended to its value
     *
     * @param string $type  The type of the key
     * @param string $key   The key name
     * @param mixed  $value The value
     *
     * @throws RuntimeException
     */
    public function redisSet(string $type, string $key, mixed $value): void
    {
        switch (strtolower($type)) {
            case 'string':
                if (!is_scalar($value)) {
                    throw new RuntimeException(
                        'If second argument of redisSet() method is "string", '
                        . 'third argument must be a scalar'
                    );
                }

                $this->redis->set($key, $value);
                break;

            case 'list':
                $this->redis->rpush($key, $value);
                break;

            case 'set':
                $this->redis->sadd($key, $value);
                break;

            case 'zset':
                if (!is_array($value)) {
                    throw new RuntimeException(
                        'If second argument of redisSet() method is "zset", '
                        . 'third argument must be an (associative) array'
                    );
                }

                $this->redis->zadd($key, $value);
                break;

            case 'hash':
                if (!is_array($value)) {
                    throw new RuntimeException(
                        'If second argument of redisSet() method is "hash", '
                        . 'third argument must be an array'
                    );
                }

                $this->redis->hmset($key, $value);
                break;

            default:
                throw new RuntimeException(
                    sprintf(
                        'Unknown type "%s" for key "%s". Allowed types are ',
                        $type,
                        $key
                    )
                    . '"string", "list", "set", "zset", "hash"'
                );
        }
    }

    /**
     * Asserts that a key does not exist or, optionally, that it doesn't have the
     * provided $value
     *
     * @param string $key   The key name
     * @param mixed  $value Optional. If specified, also checks the key has this
     * value. Booleans will be converted to 1 and 0 (even inside arrays)
     */
    public function redisHas(string $key, mixed $value = null): void
    {
        try {
            $this->assertFalse(
                $this->checkKeyExists($key, $value),
                sprintf(
                    'The key "%s" exists', $key)
                . ($value ? ' and its value matches the one provided' : ''
                )
            );
        } catch (ComparisonFailure $failure) {
            // values are different
            $this->assertFalse(false);
        }
    }

    /**
     * Asserts that a given key does not contain a given item
     *
     *
     * @param string $key       The key
     * @param mixed  $item      The item
     * @param mixed  $itemValue Optional and only used for zsets and hashes. If
     * specified, the method will also check that the $item has this value/score
     */
    public function redisNotHas(string $key, mixed $value = null): void
    {
        try {
            $this->assertFalse(
                $this->checkKeyExists($key, $value),
                sprintf('The key "%s" exists', $key)
                . ($value ? ' and its value matches the one provided' : '')
            );
        } catch (ComparisonFailure $failure) {
            // values are different
            $this->assertFalse(false);
        }
    }

    /**
     * Sends a command directly to the Redis driver. See documentation at
     * https://github.com/nrk/predis
     * Every argument that follows the $command name will be passed to it.
     *
     * @param string $command The command name
     * @return mixed
     */
    public function redisCommand(string $command): mixed
    {
        return call_user_func_array(
            [$this->redis, $command],
            array_slice(func_get_args(), 1)
        );
    }

    /**
     * Converts boolean values to "0" and "1"
     */
    private function boolToString(mixed $var): mixed
    {
        $copy = is_array($var) ? $var : [$var];

        foreach ($copy as $key => $value) {
            if (is_bool($value)) {
                $copy[$key] = $value ? '1' : '0';
            }
        }

        return is_array($var) ? $copy : $copy[0];
    }

    /**
     * Checks whether a key contains a given item
     *
     * @param string $key       The key
     * @param mixed  $item      The item
     * @param mixed   $itemValue Optional and only used for zsets and hashes. If
     * specified, the method will also check that the $item has this value/score
     *
     * @throws RuntimeException
     */
    private function checkKeyContains(string $key, mixed $item, mixed $itemValue = null): bool
    {
        $result = null;

        if (!is_scalar($item)) {
            throw new RuntimeException(
                "All arguments of [dont]seeRedisKeyContains() must be scalars"
            );
        }

        switch ($this->redis->type($key)) {
            case 'string':
                $reply = $this->redis->get($key);
                $result = strpos($reply, (string) $item) !== false;
                break;

            case 'list':
                $reply = $this->redis->lrange($key, 0, -1);
                $result = in_array($item, $reply);
                break;

            case 'set':
                $result = $this->redis->sismember($key, $item);
                break;

            case 'zset':
                $reply = $this->redis->zscore($key, $item);

                if (is_null($reply)) {
                    $result = false;
                } elseif (!is_null($itemValue)) {
                    $result = (float) $reply === (float) $itemValue;
                } else {
                    $result = true;
                }

                break;

            case 'hash':
                $reply = $this->redis->hget($key, $item);

                $result = is_null($itemValue)
                    ? !is_null($reply)
                    : (string) $reply === (string) $itemValue;
                break;

            case 'none':
                throw new RuntimeException(
                    sprintf('Key "%s" does not exist', $key)
                );
        }

        return (bool) $result;
    }

    /**
     * Checks whether a key exists and, optionally, whether it has a given $value
     *
     * @param string $key   The key name
     * @param mixed  $value Optional. If specified, also checks the key has this
     * value. Booleans will be converted to 1 and 0 (even inside arrays)
     */
    private function checkKeyExists(string $key, mixed $value): bool
    {
        $type = $this->redis->type($key);

        if ($type == 'none') {
            return false;
        }

        if (is_null($value)) {
            return true;
        }

        $value = $this->boolToString($value);

        switch ($type) {
            case 'string':
                $reply = $this->redis->get($key);
                // Allow non strict equality (2 equals '2')
                $result = $reply == $value;
                break;

            case 'list':
                $reply = $this->redis->lrange($key, 0, -1);
                // Check both arrays have the same key/value pairs + same order
                $result = $reply === $value;
                break;

            case 'set':
                $reply = $this->redis->smembers($key);
                // Only check both arrays have the same values
                sort($reply);
                sort($value);
                $result = $reply === $value;
                break;

            case 'zset':
                $reply = $this->redis->zrange($key, 0, -1, 'WITHSCORES');
                // Check both arrays have the same key/value pairs + same order
                $reply = $this->scoresToFloat($reply);
                $value = $this->scoresToFloat($value);
                $result = $reply === $value;
                break;

            case 'hash':
                $reply = $this->redis->hgetall($key);
                // Only check both arrays have the same key/value pairs (==)
                $result = $reply == $value;
                break;

            default:
                throw new RuntimeException(
                    sprintf("Unexpected value type %s", $type)
                );
        }

        if (!$result) {
            $comparatorFactory = new ComparatorFactory();
            $comparator = $comparatorFactory->getComparatorFor($value, $reply);
            $comparator->assertEquals($value, $reply);

            if ($type == 'zset') {
                /**
                 * ArrayComparator considers out of order assoc arrays as equal
                 * So we have to compare them as strings
                 */
                $replyAsString = var_export($reply, true);
                $valueAsString = var_export($value, true);
                $comparator = $comparatorFactory->getComparatorFor($valueAsString, $replyAsString);
                $comparator->assertEquals($valueAsString, $replyAsString);
            }
            // If comparator things that values are equal, then we trust it
            // This shouldn't happen in practice.
            return true;
        }

        return $result;
    }

    /**
     * Explicitly cast the scores of a Zset associative array as float/double
     *
     * @param array $arr The ZSet associative array
     */
    private function scoresToFloat(array $arr): array
    {
        foreach ($arr as $member => $score) {
            $arr[$member] = (float) $score;
        }

        return $arr;
    }
}
