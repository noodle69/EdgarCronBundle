<?php

namespace Edgar\Cron\Handler;

use Edgar\Cron\Cron\CronInterface;

/**
 * Class CronHandler
 * @package Edgar\Cron\Handler
 */
class CronHandler
{
    /** @var CronInterface[][] $crons crons list */
    private $crons = [];

    /** @var string $cronAlias cron alias */
    protected $cronAlias;

    /** @var integer $cronPriority cron priority */
    protected $cronPriority;

    /** @var array $cronArguments cron arguments */
    protected $cronArguments;

    /**
     * CronHandler constructor.
     *
     * @param string $cronAlias
     * @param integer $cronPriority
     * @param array $arguments
     */
    public function __construct(string $cronAlias = null, int $cronPriority = 0, array $arguments = [])
    {
        $this->cronAlias = $cronAlias;
        $this->cronPriority = $cronPriority;
        $this->cronArguments = $arguments;
    }

    /**
     * Add cron to crons list
     *
     * @param CronInterface $cron cron
     * @param string $alias cron alias
     * @param int $priority cron priority
     * @param string $arguments cron arguments
     */
    public function addCron(CronInterface $cron, string $alias, int $priority, string $arguments)
    {
        if (!isset($this->crons[$priority])) {
            $this->crons[$priority] = [];
        }
        $cron->setAlias($alias);
        $cron->addArguments($arguments);
        $cron->addPriority($priority);
        $this->crons[$priority][$alias] = $cron;
    }

    /**
     * Sort and return crons list
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
