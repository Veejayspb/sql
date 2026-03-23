<?php
declare(strict_types=1);

namespace Veejay\Sql\Builder;

use LogicException;
use Veejay\Sql\Factory\ExceptionFactory;
use Veejay\Sql\Trait\Execute;
use Veejay\Sql\Trait\Limit;
use Veejay\Sql\Trait\Order;
use Veejay\Sql\Trait\Where;

class Delete extends AbstractBuilder
{
    use Where;
    use Order;
    use Limit;
    use Execute;

    protected string $from;

    /**
     * FROM
     * @param string $table
     * @return static
     */
    public function from(string $table): static
    {
        $this->from = $table;
        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws LogicException
     */
    public function getSql(): string
    {
        if (empty($this->from)) {
            throw (new ExceptionFactory)->propertyNotSpecified('from');
        }

        $parts[] = 'DELETE FROM ' . $this->from;

        if (!empty($this->where)) {
            $parts[] = 'WHERE ' . $this->where;
        }

        if (!empty($this->order)) {
            $parts[] = 'ORDER BY ' . $this->arrayToString($this->order, ', ', function ($field, $sort) {
                return sprintf('%s %s', $field, $sort === SORT_ASC ? 'ASC' : 'DESC');
            });
        }

        if (!empty($this->limit)) {
            $parts[] = 'LIMIT ' . $this->limit;
        }

        return implode(' ', $parts);
    }
}
