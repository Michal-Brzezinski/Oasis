<?php

require_once __DIR__ . '/CronExpression.php';
require_once __DIR__ . '/CronParser.php';

class CronService
{
    private CronParser $parser;

    public function __construct()
    {
        $this->parser = new CronParser();
    }

    public function isDue(string $cron, DateTime $time): bool
    {
        $expr = new CronExpression($cron);

        $minutes = $this->parser->parseField($expr->getMinutes(), 0, 59);
        $hours = $this->parser->parseField($expr->getHours(), 0, 23);
        $days = $this->parser->parseField($expr->getDays(), 1, 31);
        $months = $this->parser->parseField($expr->getMonths(), 1, 12);
        $weekdays = $this->parser->parseField($expr->getWeekdays(), 0, 6);

        return in_array((int)$time->format('i'), $minutes)
            && in_array((int)$time->format('G'), $hours)
            && in_array((int)$time->format('j'), $days)
            && in_array((int)$time->format('n'), $months)
            && in_array((int)$time->format('w'), $weekdays);
    }

    public function getNextRun(string $cron, DateTime $from): DateTime
    {
        $time = clone $from;

        for ($i = 0; $i < 525600; $i++) { // max 1 rok
            if ($this->isDue($cron, $time)) {
                return $time;
            }
            $time->modify('+1 minute');
        }

        throw new RuntimeException("Unable to calculate next run for cron: $cron");
    }
}
