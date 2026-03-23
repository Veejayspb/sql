<?php
declare(strict_types=1);

namespace Veejay\Sql\Factory;

use LogicException;

class ExceptionFactory
{
    /**
     * Return exception for non specified property.
     * @param string $name
     * @return LogicException
     */
    public function propertyNotSpecified(string $name): LogicException
    {
        $message = sprintf('Property "%s" must be set', $name);
        return new LogicException($message);
    }
}
