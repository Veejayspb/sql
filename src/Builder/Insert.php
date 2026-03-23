<?php
declare(strict_types=1);

namespace Veejay\Sql\Builder;

use LogicException;
use Veejay\Sql\Factory\ExceptionFactory;
use Veejay\Sql\Trait\Execute;

class Insert extends AbstractBuilder
{
    use Execute;

    protected string $into;
    protected array $values = [];

    /**
     * INSERT INTO
     * @param string $table
     * @return static
     */
    public function into(string $table): static
    {
        $this->into = $table;
        return $this;
    }

    /**
     * VALUES
     * @param array $values
     * @return static
     */
    public function values(array $values): static
    {
        $this->values = $values;
        $this->addParams($values);
        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws LogicException
     */
    public function getSql(): string
    {
        if (empty($this->into)) {
            throw (new ExceptionFactory)->propertyNotSpecified('into');
        }

        if (empty($this->values)) {
            throw (new ExceptionFactory)->propertyNotSpecified('values');
        }

        $parts[] = 'INSERT INTO ' . $this->into;

        $fields = $this->arrayToString($this->values, ', ', fn($field) => $field);
        $parts[] = sprintf('(%s)', $fields);

        $values = $this->arrayToString($this->values, ', ', fn($field) => ":$field");
        $parts[] = sprintf('VALUES (%s)', $values);

        return implode(' ', $parts);
    }
}
