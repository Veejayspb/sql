<?php
declare(strict_types=1);

namespace Veejay\Sql\Trait;

use Veejay\Sql\Builder\AbstractBuilder;

/**
 * @mixin AbstractBuilder
 */
trait Where
{
    /**
     * @var string
     */
    protected string $where;

    /**
     * WHERE
     * @param string $condition
     * @param array $params
     * @return static
     */
    public function where(string $condition, array $params = []): static
    {
        $this->where = $condition;
        $this->addParams($params);
        return $this;
    }
}
