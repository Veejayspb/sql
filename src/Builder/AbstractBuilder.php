<?php
declare(strict_types=1);

namespace Veejay\Sql\Builder;

use Veejay\Sql\Sql;

abstract class AbstractBuilder
{
    /**
     * Placeholders for SQL expression.
     * @var array
     */
    protected array $params = [];

    /**
     * @var Sql
     */
    protected Sql $sql;

    /**
     * @param Sql $sql
     */
    public function __construct(Sql $sql)
    {
        $this->sql = $sql;
    }

    /**
     * Return placeholders for SQL expression.
     * @return array
     */
    public function getParams(): array
    {
        $sql = $this->getSql();

        return array_filter($this->params, function (string $key) use ($sql) {
            return str_contains($sql, ':' . $key);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Set placeholders list.
     * @param array $params
     * @return static
     */
    public function setParams(array $params): static
    {
        $this->params = $params;
        return $this;
    }

    /**
     * Additional placeholders.
     * @param array $params
     * @return static
     */
    public function addParams(array $params): static
    {
        $this->params = $params + $this->params;
        return $this;
    }

    /**
     * Return SQL expression.
     * @return string
     */
    abstract public function getSql(): string;

    /**
     * String representation of an array.
     * @param array $array
     * @param string $glue
     * @param callable $handler
     * @return string
     */
    protected function arrayToString(array $array, string $glue, callable $handler): string
    {
        $items = [];

        foreach ($array as $key => $value) {
            $items[] = call_user_func($handler, $key, $value);
        }

        return implode($glue, $items);
    }
}
