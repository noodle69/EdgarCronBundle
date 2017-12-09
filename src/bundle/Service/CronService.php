<?php

namespace Edgar\CronBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Edgar\Cron\Cron\CronInterface;
use Edgar\Cron\Handler\CronHandler;
use Edgar\CronBundle\Entity\EdgarCron;
use Edgar\Cron\Repository\EdgarCronRepository;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CronService
 * @package Edgar\CronBundle\Service
 */
class CronService
{
    /** @var CronHandler $cronHandler */
    protected $cronHandler;

    /** @var EdgarCronRepository $repository */
    protected $repository;

    /**
     * CronService constructor.
     *
     * @param CronHandler $cronHandler cron handler
     * @param Registry $doctrineRegistry doctrine registry
     */
    public function __construct(CronHandler $cronHandler, Registry $doctrineRegistry)
    {
        $this->cronHandler = $cronHandler;
        $entityManager = $doctrineRegistry->getManager();
        $this->repository = $entityManager->getRepository(EdgarCron::class);
    }

    /**
     * List cron commands
     *
     * @return CronInterface[] cron command list
     */
    public function getCrons(): array
    {
        return $this->cronHandler->getCrons();
    }

    /**
     * Identify if cron command is queued
     *
     * @param string $alias cron alias
     * @return bool true if cron command is queued
     */
    public function isQueued(string $alias): bool
    {
        return $this->repository->isQueued($alias);
    }

    /**
     * Add cron command to queue
     *
     * @param string $alias cron alias
     */
    public function addQueued(string $alias)
    {
        $this->repository->addQueued($alias);
    }

    /**
     * Return cron queued
     *
     * @return EdgarCron[] list cron queued
     */
    public function listQueued(): array
    {
        /** @var EdgarCron[] */
        return $this->repository->listQueued();
    }

    /**
     * Run cron command
     *
     * @param EdgarCron $edgarCron
     */
    public function run(EdgarCron $edgarCron)
    {
        $this->repository->run($edgarCron);
    }

    /**
     * End cron command
     *
     * @param EdgarCron $edgarCron
     * @param int $status
     */
    public function end(EdgarCron $edgarCron, ?int $status)
    {
        $this->repository->end($edgarCron, $status);
    }

    /**
     * List cron commands identified as queued
     *
     * @param InputInterface $input input interface
     * @param OutputInterface $output output interface
     */
    public function runQueued(InputInterface $input, OutputInterface $output)
    {
        /** @var EdgarCron[] $edgarCron */
        $edgarCrons = $this->listQueued();
        /** @var CronInterface[] $crons */
        $crons = $this->getCrons();
        /** @var CronInterface[] $cronAlias */
        $cronAlias = [];

        foreach ($crons as $cron) {
            $cronAlias[$cron->getAlias()] = $cron;
        }

        if ($edgarCrons) {
            foreach ($edgarCrons as $edgarCron) {
                if (isset($cronAlias[$edgarCron->getAlias()])) {
                    $this->run($edgarCron);
                    $status = $cronAlias[$edgarCron->getAlias()]->run($input, $output);
                    $this->end($edgarCron, $status);
                }
            }
        }
    }

    /**
     * Return cron list and status
     *
     * @return EdgarCron[] cron list with status
     */
    public function listCronsStatus(): array
    {
        /** @var EdgarCron[] $edgarCrons */
        $edgarCrons = $this->repository->listCrons();
        /** @var CronInterface[] $crons */
        $crons = $this->getCrons();
        $cronAlias = [];

        foreach ($crons as $cron) {
            $cronAlias[$cron->getAlias()] = $cron;
        }

        if ($edgarCrons) {
            foreach ($edgarCrons as $edgarCron) {
                if (isset($cronAlias[$edgarCron->getAlias()])) {
                    $cronAlias[$edgarCron->getAlias()] = $edgarCron;
                }
            }
        }
        return $cronAlias;
    }
}
