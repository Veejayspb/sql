<?php
declare(strict_types=1);

namespace Test\Unit\Builder;

use PHPUnit\Framework\TestCase;
use Veejay\Sql\Builder\Delete;

final class DeleteTest extends TestCase
{
    public function testBuilder()
    {
        $builder = $this->getBuilder();
        $builder
            ->from('tbl')
            ->where('a=:a AND b>:b', ['a' => 1, 'b' => 2])
            ->order(['id' => SORT_DESC, 'name' => SORT_ASC])
            ->limit(1);

        $expected = 'DELETE FROM tbl WHERE a=:a AND b>:b ORDER BY id DESC, name ASC LIMIT 1';
        $this->assertSame($expected, $builder->getSql());
        $this->assertEquals(['a' => 1, 'b' => 2], $builder->getParams());
    }

    /**
     * @return Delete
     */
    private function getBuilder()
    {
        return new class extends Delete {
            public function __construct() {}
        };
    }
}
