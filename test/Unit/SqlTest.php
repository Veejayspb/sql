<?php
declare(strict_types=1);

namespace Test\Unit;

use Nyholm\NSA;
use PDO;
use PHPUnit\Framework\TestCase;
use Veejay\Sql\Builder\Delete;
use Veejay\Sql\Builder\Insert;
use Veejay\Sql\Builder\Select;
use Veejay\Sql\Builder\Update;
use Veejay\Sql\Sql;

final class SqlTest extends TestCase
{
    public function testDelete()
    {
        $sql = $this->getSql();
        $builder = $sql->delete();

        $this->assertTrue(is_a($builder, Delete::class));
        $this->assertSame($sql, NSA::getProperty($builder, 'sql'));
    }

    public function testInsert()
    {
        $sql = $this->getSql();
        $builder = $sql->insert();

        $this->assertTrue(is_a($builder, Insert::class));
        $this->assertSame($sql, NSA::getProperty($builder, 'sql'));
    }

    public function testSelect()
    {
        $sql = $this->getSql();
        $builder = $sql->select();

        $this->assertTrue(is_a($builder, Select::class));
        $this->assertSame($sql, NSA::getProperty($builder, 'sql'));
    }

    public function testUpdate()
    {
        $sql = $this->getSql();
        $builder = $sql->update();

        $this->assertTrue(is_a($builder, Update::class));
        $this->assertSame($sql, NSA::getProperty($builder, 'sql'));
    }

    public function testBeginTransaction()
    {
        $sql = $this->getSql();

        $actual = $sql->beginTransaction();
        $this->assertTrue($actual);
    }

    public function testCommit()
    {
        $sql = $this->getSql();

        $actual = $sql->commit();
        $this->assertTrue($actual);
    }

    public function testRollback()
    {
        $sql = $this->getSql();

        $actual = $sql->rollback();
        $this->assertFalse($actual);
    }

    public function testInTransaction()
    {
        $sql = $this->getSql();

        $actual = $sql->inTransaction();
        $this->assertFalse($actual);

        $sql->beginTransaction();
        $actual = $sql->inTransaction();
        $this->assertTrue($actual);

        $sql->rollback();
        $actual = $sql->inTransaction();
        $this->assertTrue($actual);

        $sql->commit();
        $actual = $sql->inTransaction();
        $this->assertFalse($actual);
    }

    public function testLastInsertId()
    {
        $sql = $this->getSql();

        $actual = $sql->lastInsertId();
        $this->assertSame('lastInsertId', $actual);
    }

    /**
     * @return Sql
     */
    private function getSql()
    {
        $pdo = $this->getPDO();

        return new class($pdo) extends Sql {
            public function __construct(PDO $pdo)
            {
                $this->pdo = $pdo;
            }
        };
    }

    /**
     * @return PDO
     */
    private function getPDO()
    {
        return new class extends PDO {
            protected bool $transaction = false;

            public function __construct() {}

            public function beginTransaction(): bool
            {
                $this->transaction = true;
                return true;
            }

            public function commit(): bool
            {
                $this->transaction = false;
                return true;
            }

            public function rollBack(): bool
            {
                return false;
            }

            public function inTransaction(): bool
            {
                return $this->transaction;
            }

            public function lastInsertId($name = null): string|false
            {
                return 'lastInsertId';
            }
        };
    }
}
