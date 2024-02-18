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

namespace Phalcon\Tests\Unit\Autoload\Loader;

use Example\Namespaces\Adapter\Another;
use Example\Namespaces\Adapter\Mongo;
use Phalcon\Autoload\Loader;
use Phalcon\Tests\Fixtures\Traits\LoaderTrait;
use PHPUnit\Framework\TestCase;

use UnitTester;

use function dataDir2;
use function get_include_path;
use function is_array;
use function set_include_path;
use function spl_autoload_functions;
use function spl_autoload_register;
use function spl_autoload_unregister;

abstract class AbstractLoaderTestCase extends TestCase
{
    /**
     * @var array
     */
    protected array $loaders = [];

    /**
     * @var string
     */
    protected string $includePath = '';

    /**
     * Executed before each test
     */
    public function setUp(): void
    {
        $this->loaders = spl_autoload_functions();

        if (!is_array($this->loaders)) {
            $this->loaders = [];
        }

        $this->includePath = get_include_path();
    }

    /**
     * Executed after each test
     */
    public function tearDown(): void
    {
        $loaders = spl_autoload_functions();

        if (is_array($loaders)) {
            foreach ($loaders as $loader) {
                spl_autoload_unregister($loader);
            }
        }

        foreach ($this->loaders as $loader) {
            spl_autoload_register($loader);
        }

        set_include_path($this->includePath);
    }
}
