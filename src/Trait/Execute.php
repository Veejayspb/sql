<?php
declare(strict_types=1);

namespace Veejay\Sql\Trait;

use Veejay\Sql\Builder\AbstractBuilder;

/**
 * @mixin AbstractBuilder
 */
trait Execute
{
    /**
     * Execute an SQL expression without returning any data.
     * @return bool
     */
    public function execute(): bool
    {
        $sql = $this->getSql();
        $params = $this->getParams();

        return $this->sql->execute($sql, $params);
    }
}
