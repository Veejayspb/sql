<?php
declare(strict_types=1);

namespace Test\Unit\Builder;

use Nyholm\NSA;
use PHPUnit\Framework\TestCase;
use Veejay\Sql\Builder\AbstractBuilder;

final class AbstractBuilderTest extends TestCase
{
    public function testGetParams()
    {
        $builder = $this->getBuilder();

        NSA::setProperty($builder, 'params', ['a' => 1, 'b' => 2]);
        $expected = ['a' => 1];
        $actual = $builder->getParams();
        $this->assertSame($expected, $actual);
    }

    public function testSetParams()
    {
        $builder = $this->getBuilder();

        $expected = ['a' => 1, 'b' => 2];
        $builder->setParams($expected);
        $actual = NSA::getProperty($builder, 'params');
        $this->assertSame($expected, $actual);
    }

    public function testAddParams()
    {
        $builder = $this->getBuilder();

        NSA::setProperty($builder, 'params', ['a' => 2]);
        $builder->addParams(['a' => 1, 'b' => 2]);
        $actual = NSA::getProperty($builder, 'params');
        $this->assertSame(['a' => 1, 'b' => 2], $actual);
    }

    /**
     * @return AbstractBuilder
     */
    private function getBuilder()
    {
        return new class extends AbstractBuilder {
            public function __construct() {}

            public function getSql(): string
            {
                return 'SELECT * FROM tbl WHERE a=:a';
            }
        };
    }
}
