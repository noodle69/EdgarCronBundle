<?php

namespace Edgar\Cron\Cron;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Interface CronInterface.
 */
interface CronInterface
{
    /** set Application context */
    public function initApplication(Application $application);

    /**
     * Execute command.
     *
     * @param InputInterface  $input Input interface
     * @param OutputInterface $output Output interface
     */
    public function run(InputInterface $input, OutputInterface $output): ?int;

    /**
     * Check cron expression.
     *
     * @return bool true if cron should be executed
     */
    public function isDue(): bool;

    /**
     * Return the cron expression.
     *
     * @return string cron expression
     */
    public function getExpression(): string;

    /**
     * Add cron arguments.
     *
     * @param string $arguments cron arguments
     */
    public function addArguments(?string $arguments = null);

    /**
     * Return cron arguments.
     *
     * @return string cron arguments
     */
    public function getArguments(): string;

    /**
     * Set Cron priority.
     *
     * @param int $priority
     */
    public function addPriority(int $priority);

    /**
     * Set Cron expression.
     *
     * @param string $expression
     */
    public function addExpression(string $expression);

    /**
     * Return cron priority.
     *
     * @return int cron priority
     */
    public function getPriority(): int;

    /**
     * Set cron alias.
     *
     * @param string $alias cron Alias
     */
    public function setAlias(string $alias);

    /**
     * Return cron alias.
     *
     * @return string cron alias
     */
    public function getAlias(): string;
}
