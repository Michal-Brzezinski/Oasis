<?php

class CronExpression
{
    private string $expression;
    private array $fields;

    public function __construct(string $expression)
    {
        $this->expression = trim($expression);
        $this->fields = preg_split('/\s+/', $this->expression);

        if (count($this->fields) !== 5) {
            throw new InvalidArgumentException("Invalid CRON expression: {$expression}");
        }
    }

    public function getMinutes(): string
    {
        return $this->fields[0];
    }
    public function getHours(): string
    {
        return $this->fields[1];
    }
    public function getDays(): string
    {
        return $this->fields[2];
    }
    public function getMonths(): string
    {
        return $this->fields[3];
    }
    public function getWeekdays(): string
    {
        return $this->fields[4];
    }
}
