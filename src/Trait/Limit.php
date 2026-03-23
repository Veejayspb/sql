<?php
declare(strict_types=1);

namespace Veejay\Sql\Trait;

use Veejay\Sql\Builder\AbstractBuilder;

/**
 * @mixin AbstractBuilder
 */
trait Limit
{
    /**
     * @var int
     */
    protected int $limit = 0;

    /**
     * LIMIT
     * @param int $limit
     * @return static
     */
    public function limit(int $limit): static
    {
        $this->limit = $limit;
        return $this;
    }
}
