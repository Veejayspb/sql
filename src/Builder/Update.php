<?php
declare(strict_types=1);

namespace Veejay\Sql\Builder;

use Veejay\Sql\Factory\ExceptionFactory;
use Veejay\Sql\Trait\Execute;
use Veejay\Sql\Trait\Limit;
use Veejay\Sql\Trait\Order;
use Veejay\Sql\Trait\Where;

class Update extends AbstractBuilder
{
    use Where;
    use Order;
    use Limit;
    use Execute;

    protected string $table;
    protected string $set;

    /**
     * UPDATE
     * @param string $table
     * @return static
     */
    public function table(string $table): static
    {
        $this->table = $table;
        return $this;
    }

    /**
     * SET
     * @param array $values
     * @return static
     */
    public function set(array $values): static
    {
        $this->set = $this->arrayToString($values, ', ', fn($field) => "$field=:$field");
        $this->addParams($values);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSql(): string
    {
        if (empty($this->table)) {
            throw (new ExceptionFactory)->propertyNotSpecified('update');
        }

        if (empty($this->set)) {
            throw (new ExceptionFactory)->propertyNotSpecified('set');
        }

        $parts[] = 'UPDATE ' . $this->table;
        $parts[] = 'SET ' . $this->set;

        if (!empty($this->where)) {
            $parts[] = 'WHERE ' . $this->where;
        }

        if (!empty($this->order)) {
            $parts[] = 'ORDER BY ' . $this->arrayToString($this->order, ', ', function ($field, $sort) {
                return sprintf('%s %s', $field, $sort === SORT_ASC ? 'ASC' : 'DESC');
            });
        }

        if (0 < $this->limit) {
            $parts[] = 'LIMIT ' . $this->limit;
        }

        return implode(' ', $parts);
    }
}
