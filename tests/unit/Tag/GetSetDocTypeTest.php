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

namespace Phalcon\Tests\Unit\Tag;

use Phalcon\Tag;

final class GetSetDocTypeTest extends AbstractTagTestCase
{
    /**
     * @return array
     */
    public static function providerDoctype(): array
    {
        return [
            [Tag::XHTML10_FRAMESET],
            [Tag::XHTML10_STRICT],
            [Tag::XHTML10_TRANSITIONAL],
            [Tag::XHTML11],
            [Tag::XHTML20],
            [Tag::HTML32],
            [Tag::HTML401_STRICT],
            [Tag::HTML401_FRAMESET],
            [Tag::HTML401_TRANSITIONAL],
            [Tag::HTML5],
            [99],
        ];
    }

    /**
     * Tests Phalcon\Tag :: getDocType()
     *
     * @dataProvider providerDoctype
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2014-09-04
     */
    public function testDoctypeGetSet(int $doctype): void
    {
        Tag::resetInput();

        Tag::setDocType($doctype);

        $expected = $this->docTypeToString($doctype);
        $actual   = Tag::getDocType();
        $this->assertSame($expected, $actual);
    }
}
