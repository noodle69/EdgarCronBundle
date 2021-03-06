<?php

namespace Edgar\CronBundle\Command;

use Edgar\Cron\Repository\EdgarCronRepository;
use Edgar\CronBundle\Entity\EdgarCron;
use Edgar\CronBundle\Service\CronService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Translation\Translator;

/**
 * Class CronStatusCommand.
 */
class CronStatusCommand extends ContainerAwareCommand
{
    /** @var Translator $translator */
    private $translator;

    public function __construct(?string $name = null, Translator $translator)
    {
        parent::__construct($name);
        $this->translator = $translator;
    }

    /**
     * Configure command.
     */
    protected function configure()
    {
        $this
            ->setName('edgar:crons:status')
            ->setDescription('Edgar cron scheduler status');
    }

    /**
     * Execute command.
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
            $cronStatus = '';
            if ($cron instanceof EdgarCron) {
                switch ($cron->getStatus()) {
                    case EdgarCronRepository::STATUS_INIT:
                        $cronStatus = $this->translator->trans('Init', [], 'edgarcron');
                        break;
                    case EdgarCronRepository::STATUS_OK:
                        $cronStatus = $this->translator->trans('OK', [], 'edgarcron');
                        break;
                    case EdgarCronRepository::STATUS_ERROR:
                        $cronStatus = $this->translator->trans('Error', [], 'edgarcron');
                        break;
                    default:
                        $cronStatus = '';
                        break;
                }
            }

            $cronRows[] = [
                $cron->getAlias(),
                $cron instanceof EdgarCron ? $cron->getQueued()->format('d-m-Y H:i') : false,
                $cron instanceof EdgarCron ? $cron->getStarted()->format('d-m-Y H:i') : false,
                $cron instanceof EdgarCron ? $cron->getEnded()->format('d-m-Y H:i') : false,
                $cronStatus,
            ];
        }

        $table = new Table($output);
        $table
            ->setHeaders(['Cron Alias', 'Queued', 'Started', 'Ended', 'Status'])
            ->setRows($cronRows);
        $table->render();
    }
}
