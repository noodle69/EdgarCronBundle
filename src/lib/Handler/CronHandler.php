<?php

namespace Edgar\Cron\Handler;

use Edgar\Cron\Cron\CronInterface;

/**
 * Class CronHandler.
 */
class CronHandler
{
    /** @var CronInterface[][] $crons crons list */
    private $crons = [];

    /** @var string $cronAlias cron alias */
    protected $cronAlias;

    /** @var int $cronPriority cron priority */
    protected $cronPriority;

    protected $cronExpression;

    /** @var array $cronArguments cron arguments */
    protected $cronArguments;

    /**
     * CronHandler constructor.
     *
     * @param string $cronAlias
     * @param int $cronPriority
     * @param array $arguments
     */
    public function __construct(
        string $cronAlias = null,
        int $cronPriority = 0,
        string $cronExpression = '* * * * *',
        array $arguments = []
    ) {
        $this->cronAlias = $cronAlias;
        $this->cronPriority = $cronPriority;
        $this->cronExpression = $cronExpression;
        $this->cronArguments = $arguments;
    }

    /**
     * Add cron to crons list.
     *
     * @param CronInterface $cron cron
     * @param string $alias cron alias
     * @param int $priority cron priority
     * @param string $expression
     * @param string $arguments cron arguments
     */
    public function addCron(CronInterface $cron, string $alias, int $priority, string $expression, string $arguments)
    {
        if (!isset($this->crons[$priority])) {
            $this->crons[$priority] = [];
        }
        $cron->setAlias($alias);
        $cron->addArguments($arguments);
        $cron->addPriority($priority);
        $cron->addExpression($expression);
        $this->crons[$priority][$alias] = $cron;
    }

    /**
     * Sort and return crons list.
     *
     * @return CronInterface[] crons list
     */
    public function getCrons(): array
    {
        ksort($this->crons);
        $crons = [];

        foreach ($this->crons as $priority => $cs) {
            foreach ($cs as $cron) {
                $crons[$priority . ';' . $cron->getAlias()] = $cron;
            }
        }

        return $crons;
    }
}
