<?php

namespace Edgar\CronBundle\Command;

use Edgar\Cron\Cron\CronInterface;
use Edgar\CronBundle\Service\CronService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CronCommand
 *
 * @package Edgar\CronBundle\Command
 */
class CronCommand extends ContainerAwareCommand
{
    /**
     * Configure command
     */
    protected function configure()
    {
        $this
            ->setName('edgar:crons:run')
            ->setDescription('Edgar cron scheduler');
    }

    /**
     * Execute command
     *
     * @param InputInterface  $input Input interface
     * @param OutputInterface $output Output interface
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var CronService $cronService */
        $cronService = $this->getContainer()->get(CronService::class);
        /** @var CronInterface[] $cronsDue */
        $cronsDue = array();

        /** @var CronInterface[] $crons */
        $crons = $cronService->getCrons();
        foreach ($crons as $cron) {
            if ($cron->isDue()) {
                $cronsDue[] = $cron;
            }
        }

        foreach ($cronsDue as $cron) {
            if (!$cronService->isQueued($cron->getAlias())) {
                $cronService->addQueued($cron->getAlias());
            }
        }

        $cronService->runQueued($input, $output);
    }
}
