<?php
declare(strict_types=1);

namespace Test\Unit\Builder;

use PHPUnit\Framework\TestCase;
use Veejay\Sql\Builder\Select;

final class SelectTest extends TestCase
{
    public function testBuilder()
    {
        $builder = $this->getBuilder();
        $builder
            ->select('t.id')
            ->from('tbl', 't')
            ->leftJoin('left', 'l.parent_id=t.id', 'l')
            ->where('t.id<:id', ['id' => 5])
            ->group('t.id', 't.status')
            ->having('t.name=:name', ['name' => 'abc'])
            ->order(['t.id' => SORT_DESC])
            ->offset(2)
            ->limit(8);

        $expected = 'SELECT t.id FROM tbl AS t LEFT JOIN left AS l ON l.parent_id=t.id WHERE t.id<:id GROUP BY t.id, t.status HAVING t.name=:name ORDER BY t.id DESC OFFSET 2 LIMIT 8';
        $this->assertSame($expected, $builder->getSql());
        $this->assertEquals(['id' => 5, 'name' => 'abc'], $builder->getParams());
    }

    /**
     * @return Select
     */
    private function getBuilder()
    {
        return new class extends Select {
            public function __construct() {}
        };
    }
}
