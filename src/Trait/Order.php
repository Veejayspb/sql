<?php
declare(strict_types=1);

namespace Veejay\Sql\Trait;

use Veejay\Sql\Builder\AbstractBuilder;

/**
 * @mixin AbstractBuilder
 */
trait Order
{
    /**
     * @var array
     */
    protected array $order = [];

    /**
     * ORDER BY
     * @param array $columns
     * @return static
     */
    public function order(array $columns = []): static
    {
        $this->order = $columns;
        return $this;
    }
}
