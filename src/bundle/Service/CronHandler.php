<?php

namespace Edgar\CronBundle\Service;

use Edgar\Cron\Cron\AbstractCron;
use Edgar\Cron\Cron\CronInterface;

/**
 * Class CronHandler
 * @package Edgar\Cron\Cron
 */
class CronHandler extends AbstractCron
{
    /** @var CronInterface[][] $crons crons list */
    private $crons = array();

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
    public function __construct(string $cronAlias = '', int $cronPriority = 0, array $arguments = array())
    {
        parent::__construct($cronAlias);
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
            $this->crons[$priority] = array();
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
