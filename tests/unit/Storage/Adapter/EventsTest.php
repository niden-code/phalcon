<?php

/**
 * This file is part of the Phalcon Framework.
 * (c) Phalcon Team <team@phalcon.io>
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Phalcon\Tests\Unit\Storage\Adapter;

use Phalcon\Events\Event;
use Phalcon\Events\Manager;
use Phalcon\Storage\SerializerFactory;
use Phalcon\Tests\Unit\Storage\AbstractStorageTestCase;
use RuntimeException;

final class EventsTest extends AbstractStorageTestCase
{
    /**
     * Tests Cache\Adapter\* :: getEventsManager()
     *
     * @dataProvider getExamples
     *
     * @author       n[oO]ne <lominum@protonmail.com>
     * @since        2024-06-07
     */
    public function testCacheAdapterGetEventsManagerNotSet(
        string $adapterClass,
        array $options,
        string $extension
    ): void {
        if (!empty($extension)) {
            $this->checkExtensionIsLoaded($extension);
        }

        $serializer = new SerializerFactory();
        $adapter    = new $adapterClass($serializer, $options);

        $actual = $adapter->getEventsManager();
        $this->assertNull($actual);
    }

    /**
     * Tests Cache\Adapter\* :: getEventsManager()
     *
     * @dataProvider getExamples
     * *
     * @author       n[oO]ne <lominum@protonmail.com>
     * @since        2024-06-07
     */
    public function testCacheAdapterGetEventsManagerSet(
        string $adapterClass,
        array $options,
        string $extension
    ): void {
        if (!empty($extension)) {
            $this->checkExtensionIsLoaded($extension);
        }

        $serializer = new SerializerFactory();
        $adapter    = new $adapterClass($serializer, $options);

        $adapter->setEventsManager(new Manager());

        $expected = Manager::class;
        $actual   = $adapter->getEventsManager();
        $this->assertInstanceOf($expected, $actual);
    }

    /**
     * Tests Phalcon\Storage\Adapter\* :: events - afterDecrement
     *
     * @dataProvider getExamples
     * @author       n[oO]ne <lominum@protonmail.com>
     * @since        2024-06-07
     */
    public function testStorageAdapterEventsAfterDecrement(
        string $adapterClass,
        array $options,
        string $extension,
    ): void {
        if (!empty($extension)) {
            $this->checkExtensionIsLoaded($extension);
        }

        $counter    = 0;
        $serializer = new SerializerFactory();
        $adapter    = new $adapterClass($serializer, $options);
        $manager    = new Manager();

        $manager->attach(
            'storage:afterDecrement',
            static function (Event $event) use (&$counter): void {
                $counter++;
                $data = $event->getData();
                $data === 'test' ?: throw new RuntimeException('wrong key');
            }
        );

        $adapter->setEventsManager($manager);

        call_user_func_array([$adapter, 'decrement'], ['test']);
        call_user_func_array([$adapter, 'decrement'], ['test']);

        $this->assertSame(2, $counter);
    }

    /**
     * Tests Phalcon\Storage\Adapter\* :: events - afterDelete
     *
     * @dataProvider getExamples
     * @author       n[oO]ne <lominum@protonmail.com>
     * @since        2024-06-07
     */
    public function testStorageAdapterEventsAfterDelete(
        string $adapterClass,
        array $options,
        string $extension
    ): void {
        if (!empty($extension)) {
            $this->checkExtensionIsLoaded($extension);
        }

        $counter    = 0;
        $serializer = new SerializerFactory();
        $adapter    = new $adapterClass($serializer, $options);
        $manager    = new Manager();

        $manager->attach(
            'storage:afterDelete',
            static function (Event $event) use (&$counter): void {
                $counter++;
                $data = $event->getData();
                $data === 'test' ?: throw new RuntimeException('wrong key');
            }
        );

        $adapter->setEventsManager($manager);

        call_user_func_array([$adapter, 'delete'], ['test']);
        call_user_func_array([$adapter, 'delete'], ['test']);

        $this->assertSame(2, $counter);
    }

    /**
     * Tests Phalcon\Storage\Adapter\* :: events - afterGet
     *
     * @dataProvider getExamples
     * @author       n[oO]ne <lominum@protonmail.com>
     * @since        2024-06-07
     */
    public function testStorageAdapterEventsAfterGet(
        string $adapterClass,
        array $options,
        string $extension
    ): void {
        if (!empty($extension)) {
            $this->checkExtensionIsLoaded($extension);
        }

        $counter    = 0;
        $serializer = new SerializerFactory();
        $adapter    = new $adapterClass($serializer, $options);
        $manager    = new Manager();

        $manager->attach(
            'storage:afterGet',
            static function (Event $event) use (&$counter): void {
                $counter++;
                $data = $event->getData();
                $data === 'test' ?: throw new RuntimeException('wrong key');
            }
        );

        $adapter->setEventsManager($manager);

        call_user_func_array([$adapter, 'get'], ['test']);
        call_user_func_array([$adapter, 'get'], ['test']);

        $this->assertSame(2, $counter);
    }

    /**
     * Tests Phalcon\Storage\Adapter\* :: events - afterHas
     *
     * @dataProvider getExamples
     * @author       n[oO]ne <lominum@protonmail.com>
     * @since        2024-06-07
     */
    public function testStorageAdapterEventsAfterHas(
        string $adapterClass,
        array $options,
        string $extension
    ): void {
        if (!empty($extension)) {
            $this->checkExtensionIsLoaded($extension);
        }

        $counter    = 0;
        $serializer = new SerializerFactory();
        $adapter    = new $adapterClass($serializer, $options);
        $manager    = new Manager();

        $manager->attach(
            'storage:afterHas',
            static function (Event $event) use (&$counter): void {
                $counter++;
                $data = $event->getData();
                $data === 'test' ?: throw new RuntimeException('wrong key');
            }
        );

        $adapter->setEventsManager($manager);

        call_user_func_array([$adapter, 'has'], ['test']);
        call_user_func_array([$adapter, 'has'], ['test']);

        $this->assertSame(2, $counter);
    }

    /**
     * Tests Phalcon\Storage\Adapter\* :: events - afterIncrement
     *
     * @dataProvider getExamples
     * @author       n[oO]ne <lominum@protonmail.com>
     * @since        2024-06-07
     */
    public function testStorageAdapterEventsAfterIncrement(
        string $adapterClass,
        array $options,
        string $extension
    ): void {
        if (!empty($extension)) {
            $this->checkExtensionIsLoaded($extension);
        }

        $counter    = 0;
        $serializer = new SerializerFactory();
        $adapter    = new $adapterClass($serializer, $options);
        $manager    = new Manager();

        $manager->attach(
            'storage:afterIncrement',
            static function (Event $event) use (&$counter): void {
                $counter++;
                $data = $event->getData();
                $data === 'test' ?: throw new RuntimeException('wrong key');
            }
        );

        $adapter->setEventsManager($manager);

        call_user_func_array([$adapter, 'increment'], ['test']);
        call_user_func_array([$adapter, 'increment'], ['test']);

        $this->assertSame(2, $counter);
    }

    /**
     * Tests Phalcon\Storage\Adapter\* :: events - afterSet
     *
     * @dataProvider getExamples
     * @author       n[oO]ne <lominum@protonmail.com>
     * @since        2024-06-07
     */
    public function testStorageAdapterEventsAfterSet(
        string $adapterClass,
        array $options,
        string $extension
    ): void {
        if (!empty($extension)) {
            $this->checkExtensionIsLoaded($extension);
        }

        $counter    = 0;
        $serializer = new SerializerFactory();
        $adapter    = new $adapterClass($serializer, $options);
        $manager    = new Manager();

        $manager->attach(
            'storage:afterSet',
            static function (Event $event) use (&$counter): void {
                $counter++;
                $data = $event->getData();
                $data === 'test' ?: throw new RuntimeException('wrong key');
            }
        );

        $adapter->setEventsManager($manager);

        call_user_func_array([$adapter, 'set'], ['test', 'test']);
        call_user_func_array([$adapter, 'set'], ['test', 'test']);

        $this->assertSame(2, $counter);
    }

    /**
     * Tests Phalcon\Storage\Adapter\* :: events - beforeDecrement
     *
     * @dataProvider getExamples
     * @author       n[oO]ne <lominum@protonmail.com>
     * @since        2024-06-07
     */
    public function testStorageAdapterEventsBeforeDecrement(
        string $adapterClass,
        array $options,
        string $extension
    ): void {
        if (!empty($extension)) {
            $this->checkExtensionIsLoaded($extension);
        }

        $counter    = 0;
        $serializer = new SerializerFactory();
        $adapter    = new $adapterClass($serializer, $options);
        $manager    = new Manager();

        $manager->attach(
            'storage:beforeDecrement',
            static function (Event $event) use (&$counter): void {
                $counter++;
                $data = $event->getData();
                $data === 'test' ?: throw new RuntimeException('wrong key');
            }
        );

        $adapter->setEventsManager($manager);

        call_user_func_array([$adapter, 'decrement'], ['test']);
        call_user_func_array([$adapter, 'decrement'], ['test']);

        $this->assertSame(2, $counter);
    }

    /**
     * Tests Phalcon\Storage\Adapter\* :: events - beforeDelete
     *
     * @dataProvider getExamples
     * @author       n[oO]ne <lominum@protonmail.com>
     * @since        2024-06-07
     */
    public function testStorageAdapterEventsBeforeDelete(
        string $adapterClass,
        array $options,
        string $extension
    ): void {
        if (!empty($extension)) {
            $this->checkExtensionIsLoaded($extension);
        }

        $counter    = 0;
        $serializer = new SerializerFactory();
        $adapter    = new $adapterClass($serializer, $options);
        $manager    = new Manager();

        $manager->attach(
            'storage:beforeDelete',
            static function (Event $event) use (&$counter): void {
                $counter++;
                $data = $event->getData();
                $data === 'test' ?: throw new RuntimeException('wrong key');
            }
        );

        $adapter->setEventsManager($manager);

        call_user_func_array([$adapter, 'delete'], ['test']);
        call_user_func_array([$adapter, 'delete'], ['test']);

        $this->assertSame(2, $counter);
    }

    /**
     * Tests Phalcon\Storage\Adapter\* :: events - beforeGet
     *
     * @dataProvider getExamples
     * @author       n[oO]ne <lominum@protonmail.com>
     * @since        2024-06-07
     */
    public function testStorageAdapterEventsBeforeGet(
        string $adapterClass,
        array $options,
        string $extension
    ): void {
        if (!empty($extension)) {
            $this->checkExtensionIsLoaded($extension);
        }

        $counter    = 0;
        $serializer = new SerializerFactory();
        $adapter    = new $adapterClass($serializer, $options);
        $manager    = new Manager();

        $manager->attach(
            'storage:beforeGet',
            static function (Event $event) use (&$counter): void {
                $counter++;
                $data = $event->getData();
                $data === 'test' ?: throw new RuntimeException('wrong key');
            }
        );

        $adapter->setEventsManager($manager);

        call_user_func_array([$adapter, 'get'], ['test']);
        call_user_func_array([$adapter, 'get'], ['test']);

        $this->assertSame(2, $counter);
    }

    /**
     * Tests Phalcon\Storage\Adapter\* :: events - beforeHas
     *
     * @dataProvider getExamples
     * @author       n[oO]ne <lominum@protonmail.com>
     * @since        2024-06-07
     */
    public function testStorageAdapterEventsBeforeHas(
        string $adapterClass,
        array $options,
        string $extension
    ): void {
        if (!empty($extension)) {
            $this->checkExtensionIsLoaded($extension);
        }

        $counter    = 0;
        $serializer = new SerializerFactory();
        $adapter    = new $adapterClass($serializer, $options);
        $manager    = new Manager();

        $manager->attach(
            'storage:beforeHas',
            static function (Event $event) use (&$counter): void {
                $counter++;
                $data = $event->getData();
                $data === 'test' ?: throw new RuntimeException('wrong key');
            }
        );

        $adapter->setEventsManager($manager);

        call_user_func_array([$adapter, 'has'], ['test']);
        call_user_func_array([$adapter, 'has'], ['test']);

        $this->assertSame(2, $counter);
    }

    /**
     * Tests Phalcon\Storage\Adapter\* :: events - beforeIncrement
     *
     * @dataProvider getExamples
     * @author       n[oO]ne <lominum@protonmail.com>
     * @since        2024-06-07
     */
    public function testStorageAdapterEventsBeforeIncrement(
        string $adapterClass,
        array $options,
        string $extension
    ): void {
        if (!empty($extension)) {
            $this->checkExtensionIsLoaded($extension);
        }

        $counter    = 0;
        $serializer = new SerializerFactory();
        $adapter    = new $adapterClass($serializer, $options);
        $manager    = new Manager();

        $manager->attach(
            'storage:beforeIncrement',
            static function (Event $event) use (&$counter): void {
                $counter++;
                $data = $event->getData();
                $data === 'test' ?: throw new RuntimeException('wrong key');
            }
        );

        $adapter->setEventsManager($manager);

        call_user_func_array([$adapter, 'increment'], ['test']);
        call_user_func_array([$adapter, 'increment'], ['test']);

        $this->assertSame(2, $counter);
    }

    /**
     * Tests Phalcon\Storage\Adapter\* :: events - beforeSet
     *
     * @dataProvider getExamples
     * @author       n[oO]ne <lominum@protonmail.com>
     * @since        2024-06-07
     */
    public function testStorageAdapterEventsBeforeSet(
        string $adapterClass,
        array $options,
        string $extension
    ): void {
        if (!empty($extension)) {
            $this->checkExtensionIsLoaded($extension);
        }

        $counter    = 0;
        $serializer = new SerializerFactory();
        $adapter    = new $adapterClass($serializer, $options);
        $manager    = new Manager();

        $manager->attach(
            'storage:beforeSet',
            static function (Event $event) use (&$counter): void {
                $counter++;
                $data = $event->getData();
                $data === 'test' ?: throw new RuntimeException('wrong key');
            }
        );

        $adapter->setEventsManager($manager);

        call_user_func_array([$adapter, 'set'], ['test', 'test']);
        call_user_func_array([$adapter, 'set'], ['test', 'test']);

        $this->assertSame(2, $counter);
    }
}
