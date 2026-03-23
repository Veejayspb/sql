<?php
declare(strict_types=1);

namespace Veejay\Sql\Builder;

use InvalidArgumentException;
use LogicException;
use Veejay\Sql\Factory\ExceptionFactory;
use Veejay\Sql\Trait\Limit;
use Veejay\Sql\Trait\Order;
use Veejay\Sql\Trait\Where;

class Select extends AbstractBuilder
{
    use Where;
    use Order;
    use Limit;

    public const LEFT_JOIN = 'LEFT JOIN';
    public const RIGHT_JOIN = 'RIGHT JOIN';
    public const INNER_JOIN = 'INNER JOIN';
    public const CROSS_JOIN = 'CROSS JOIN';

    protected array $select = ['*'];
    protected string $from;
    protected array $join = [];
    protected array $group = [];
    protected string $having;
    protected int $offset = 0;

    /**
     * SELECT
     * @param ...$columns
     * @return static
     */
    public function select(...$columns): static
    {
        $this->select = array_filter($columns, fn($column) => is_string($column));
        return $this;
    }

    /**
     * FROM
     * @param string $table
     * @param string|null $alias
     * @return static
     */
    public function from(string $table, ?string $alias = null): static
    {
        $this->from = $table;

        if (!is_null($alias)) {
            $this->from .= ' AS ' . $alias;
        }

        return $this;
    }

    /**
     * JOIN
     * @param string $type
     * @param string $table
     * @param string $on
     * @param string|null $alias
     * @return static
     * @throws InvalidArgumentException
     */
    public function join(string $type, string $table, string $on, ?string $alias = null): static
    {
        if (is_null($alias)) {
            $this->join[] = sprintf('%s %s ON %s', $type, $table, $on);
        } else {
            $this->join[] = sprintf('%s %s AS %s ON %s', $type, $table, $alias, $on);
        }

        return $this;
    }

    /**
     * LEFT JOIN
     * @param string $table
     * @param string $on
     * @param string|null $alias
     * @return static
     * @throws InvalidArgumentException
     */
    public function leftJoin(string $table, string $on, ?string $alias = null): static
    {
        $this->join(self::LEFT_JOIN, $table, $on, $alias);
        return $this;
    }

    /**
     * RIGHT JOIN
     * @param string $table
     * @param string $on
     * @param string|null $alias
     * @return static
     * @throws InvalidArgumentException
     */
    public function rightJoin(string $table, string $on, ?string $alias = null): static
    {
        $this->join(self::RIGHT_JOIN, $table, $on, $alias);
        return $this;
    }

    /**
     * INNER JOIN
     * @param string $table
     * @param string $on
     * @param string|null $alias
     * @return static
     * @throws InvalidArgumentException
     */
    public function innerJoin(string $table, string $on, ?string $alias = null): static
    {
        $this->join(self::INNER_JOIN, $table, $on, $alias);
        return $this;
    }

    /**
     * CROSS JOIN
     * @param string $table
     * @param string $on
     * @param string|null $alias
     * @return static
     * @throws InvalidArgumentException
     */
    public function crossJoin(string $table, string $on, ?string $alias = null): static
    {
        $this->join(self::CROSS_JOIN, $table, $on, $alias);
        return $this;
    }

    /**
     * GROUP BY
     * @param ...$args
     * @return static
     */
    public function group(...$args): static
    {
        $this->group = $args;
        return $this;
    }

    /**
     * HAVING
     * @param string $condition
     * @param array $params
     * @return static
     */
    public function having(string $condition, array $params = []): static
    {
        $this->having = $condition;
        $this->addParams($params);
        return $this;
    }

    /**
     * OFFSET
     * @param int $offset
     * @return static
     */
    public function offset(int $offset): static
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws LogicException
     */
    public function getSql(): string
    {
        if (empty($this->select)) {
            throw (new ExceptionFactory)->propertyNotSpecified('select');
        }

        if (empty($this->from)) {
            throw (new ExceptionFactory)->propertyNotSpecified('from');
        }

        $parts[] = 'SELECT ' . implode(', ', $this->select);
        $parts[] = 'FROM ' . $this->from;

        if (!empty($this->join)) {
            $parts[] = implode(' ', $this->join);
        }

        if (!empty($this->where)) {
            $parts[] = 'WHERE ' . $this->where;
        }

        if (!empty($this->group)) {
            $parts[] = 'GROUP BY ' . implode(', ', $this->group);
        }

        if (!empty($this->having)) {
            $parts[] = 'HAVING ' . $this->having;
        }

        if (!empty($this->order)) {
            $parts[] = 'ORDER BY ' . $this->arrayToString($this->order, ', ', function ($field, $sort) {
                return sprintf('%s %s', $field, $sort === SORT_ASC ? 'ASC' : 'DESC');
            });
        }

        if (0 < $this->offset) {
            $parts[] = 'OFFSET ' . $this->offset;
        }

        if (0 < $this->limit) {
            $parts[] = 'LIMIT ' . $this->limit;
        }

        return implode(' ', $parts);
    }

    /**
     * Execute an SQL expression and return data.
     * @return array
     */
    public function query(): array
    {
        $sql = $this->getSql();
        $params = $this->getParams();

        return $this->sql->query($sql, $params);
    }
}
