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

namespace Phalcon\Tests\Unit\Di;

use Phalcon\Di\Di;
use Phalcon\Di\Exception;
use Phalcon\Html\Escaper;
use Phalcon\Support\Collection;
use Phalcon\Tests\Support\AbstractUnitTestCase;

final class SetTest extends AbstractUnitTestCase
{
    /**
     * Unit Tests Phalcon\Di :: set()
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2019-09-09
     */
    public function testDiSet(): void
    {
        $data = [
            [
                'escaper',
                Escaper::class,
                Escaper::class,
            ],
            [
                'escaper',
                function () {
                    return new Escaper();
                },
                Escaper::class,
            ],
            [
                'escaper',
                [
                    'className' => Escaper::class,
                ],
                Escaper::class,
            ],
        ];

        foreach ($data as $example) {
            $container = new Di();

            // set non shared service
            $container->set($example[0], $example[1]);

            $class  = $example[2];
            $actual = $container->get($example[0]);
            $this->assertInstanceOf($class, $actual);
        }
    }

    /**
     * Unit Tests Phalcon\Di :: set() - alias
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-09-09
     */
    public function testDiSetAlias(): void
    {
        $container = new Di();
        $escaper   = new Escaper();

        $container->set('alias', Escaper::class);
        $container->set(Escaper::class, $escaper);

        $class  = Escaper::class;
        $actual = $container->get('alias');
        $this->assertInstanceOf($class, $actual);
    }

    /**
     * Unit Tests Phalcon\Di :: set() - shared
     *
     * @return void
     *
     * @throws Exception
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-09-09
     */
    public function testDiSetShared(): void
    {
        $container = new Di();

        // set non shared service
        $container->set('escaper', Escaper::class);

        $class  = Escaper::class;
        $actual = $container->get('escaper');
        $this->assertInstanceOf($class, $actual);

        $escaper = $container->getService('escaper');
        $actual  = $escaper->isShared();
        $this->assertFalse($actual);

        // set shared service
        $container->set('collection', Collection::class, true);

        $class  = Collection::class;
        $actual = $container->get('collection');
        $this->assertInstanceOf($class, $actual);

        $collection = $container->getService('collection');
        $actual     = $collection->isShared();
        $this->assertTrue($actual);
    }
}
