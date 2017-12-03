<?php

namespace Edgar\CronBundle\Command;

use Edgar\Cron\Entity\EdgarCron;
use Edgar\CronBundle\Service\CronService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CronStatusCommand extends ContainerAwareCommand
{
    /**
     * Configure command
     */
    protected function configure()
    {
        $this
            ->setName('edgar:crons:status')
            ->setDescription('Edgar cron scheduler status');
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
        $crons = $cronService->listCronsStatus();
        $cronRows = [];

        foreach ($crons as $cron) {
            $cronRows[] = array(
                $cron->getAlias(),
                $cron instanceof EdgarCron ? $cron->getQueued()->format('d-m-Y H:i') : false,
                $cron instanceof EdgarCron ? $cron->getStarted()->format('d-m-Y H:i') : false,
                $cron instanceof EdgarCron ? $cron->getEnded()->format('d-m-Y H:i') : false,
                $cron instanceof EdgarCron ? $cron->getStatus() : false
            );
        }

        $table = new Table($output);
        $table
            ->setHeaders(array('Cron Alias', 'Queued', 'Started', 'Ended', 'Status'))
            ->setRows($cronRows);
        $table->render();
    }
}
