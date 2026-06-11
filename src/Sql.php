<?php
declare(strict_types=1);

namespace Veejay\Sql;

use PDO;
use PDOException;
use PDOStatement;
use Veejay\Sql\Builder\Delete;
use Veejay\Sql\Builder\Insert;
use Veejay\Sql\Builder\Select;
use Veejay\Sql\Builder\Update;

class Sql
{
    /**
     * PDO instance.
     * @var PDO
     */
    protected PDO $pdo;

    /**
     * @param string $dsn
     * @param string|null $username
     * @param string|null $password
     * @param array|null $options
     * @see PDO::__construct()
     */
    public function __construct(string $dsn, ?string $username = null, ?string $password = null, ?array $options = null)
    {
        $this->pdo = new PDO($dsn, $username, $password, $options);
    }

    /**
     * DELETE - sql builder.
     * @return Delete
     */
    public function delete(): Delete
    {
        return new Delete($this);
    }

    /**
     * INSERT - sql builder.
     * @return Insert
     */
    public function insert(): Insert
    {
        return new Insert($this);
    }

    /**
     * SELECT - sql builder.
     * @return Select
     */
    public function select(): Select
    {
        return new Select($this);
    }

    /**
     * UPDATE - sql builder.
     * @return Update
     */
    public function update(): Update
    {
        return new Update($this);
    }

    /**
     * Transaction begin.
     * @return bool
     */
    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    /**
     * Transaction commit.
     * @return bool
     */
    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    /**
     * Transaction rollback.
     * @return bool
     */
    public function rollback(): bool
    {
        return $this->pdo->rollBack();
    }

    /**
     * Checks if inside a transaction.
     * @return bool
     */
    public function inTransaction(): bool
    {
        return $this->pdo->inTransaction();
    }

    /**
     * Get last insert ID.
     * @return string|null
     */
    public function lastInsertId(): ?string
    {
        $result = $this->pdo->lastInsertId();
        return $result === false ? null : $result;
    }

    /**
     * Execute an SQL expression without returning any data.
     * @param string $sql
     * @param array $params
     * @return bool
     * @throws PDOException
     */
    public function execute(string $sql, array $params = []): bool
    {
        return $this
            ->pdoPrepare($sql)
            ->execute($params);
    }

    /**
     * Execute an SQL expression and return data.
     * @param string $sql
     * @param array $params
     * @return array
     * @throws PDOException
     */
    public function query(string $sql, array $params = []): array
    {
        $stmt = $this->pdoPrepare($sql);
        $result = $stmt->execute($params);

        if ($result === false) {
            throw new PDOException('PDO execute error');
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Create new table.
     * @param string $name - table name
     * @param array $columns - columns list (key - column name, value - column type expression)
     * @param array $keys - additional keys list
     * PRIMARY KEY (`id`)
     * FOREIGN KEY (`record_id`) REFERENCES `table` (`id`)
     * @return bool
     */
    public function createTable(string $name, array $columns, array $keys = []): bool
    {
        $sql = "CREATE TABLE `$name` (";

        $items = [];
        foreach ($columns as $column => $expression) {
            $items[] = "`$column` $expression";
        }
        $sql .= implode(', ', $items);

        $sql .= empty($keys) ? '' : ', ';
        $sql .= implode(', ', $keys);
        $sql .= ')';

        return $this->execute($sql);
    }

    /**
     * Drop table.
     * @param string $name - table name
     * @return bool
     */
    public function dropTable(string $name): bool
    {
        return $this->execute("DROP TABLE `$name`");
    }

    /**
     * Prepare an SQL expression.
     * @param string $sql
     * @return PDOStatement
     */
    protected function pdoPrepare(string $sql): PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);

        if ($stmt === false) {
            throw new PDOException('PDO prepare error');
        }

        return $stmt;
    }
}
