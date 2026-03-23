<?php
declare(strict_types=1);

namespace Test\Unit\Builder;

use PHPUnit\Framework\TestCase;
use Veejay\Sql\Builder\Update;

final class UpdateTest extends TestCase
{
    public function testBuilder()
    {
        $builder = $this->getBuilder();
        $builder
            ->table('tbl')
            ->set(['id' => 2, 'name' => 'qwerty'])
            ->where('status=:status', ['status' => 0])
            ->order(['id' => SORT_DESC])
            ->limit(4);

        $expected = 'UPDATE tbl SET id=:id, name=:name WHERE status=:status ORDER BY id DESC LIMIT 4';
        $this->assertSame($expected, $builder->getSql());
        $this->assertEquals(['id' => 2, 'name' => 'qwerty', 'status' => 0], $builder->getParams());
    }

    /**
     * @return Update
     */
    private function getBuilder()
    {
        return new class extends Update {
            public function __construct() {}
        };
    }
}
