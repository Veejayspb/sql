<?php
declare(strict_types=1);

namespace Test\Unit\Builder;

use PHPUnit\Framework\TestCase;
use Veejay\Sql\Builder\Insert;

final class InsertTest extends TestCase
{
    public function testBuilder()
    {
        $builder = $this->getBuilder();
        $builder
            ->into('tbl')
            ->values(['id' => 1, 'name' => 'a']);

        $expected = 'INSERT INTO tbl (id, name) VALUES (:id, :name)';
        $this->assertSame($expected, $builder->getSql());
        $this->assertEquals(['id' => 1, 'name' => 'a'], $builder->getParams());
    }

    /**
     * @return Insert
     */
    private function getBuilder()
    {
        return new class extends Insert {
            public function __construct() {}
        };
    }
}
