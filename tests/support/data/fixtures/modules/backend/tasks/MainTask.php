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

namespace Phalcon\Tests1\Modules\Backend\Tasks;

use Exception;
use Phalcon\Cli\Task;

class MainTask extends Task
{
    public function throwAction()
    {
        throw new Exception('Task Run');
    }

    public function noopAction()
    {
        return;
    }
}
