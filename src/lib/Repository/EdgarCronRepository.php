<?php

namespace Edgar\Cron\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Edgar\CronBundle\Entity\EdgarCron;

/**
 * Class EdgarCronRepository.
 */
class EdgarCronRepository extends EntityRepository
{
    public const STATUS_INIT = 0;
    public const STATUS_OK = 1;
    public const STATUS_ERROR = 2;

    /**
     * Identify of cron command is queued.
     *
     * @param string $alias cron alias
     *
     * @return bool true if cron is already queued
     */
    public function isQueued(string $alias): bool
    {
        $query = $this->createQueryBuilder('c')
            ->where('c.alias = :alias')
            ->andWhere('c.ended is NULL')
            ->setParameter('alias', $alias)
            ->getQuery();

        try {
            $result = $query->setMaxResults(1)->getOneOrNullResult();
            if ($result) {
                return true;
            }
        } catch (NonUniqueResultException $e) {
            return false;
        }

        return false;
    }

    /**
     * Add cron to queued.
     *
     * @param string $alias cron alias
     */
    public function addQueued($alias)
    {
        $query = $this->createQueryBuilder('c')
            ->where('c.alias = :alias')
            ->setParameter('alias', $alias)
            ->getQuery();

        try {
            /** @var EdgarCron $edgarCron */
            $edgarCron = $query->setMaxResults(1)->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            $edgarCron = new EdgarCron();
            $edgarCron->setAlias($alias);
        }

        if (!$edgarCron) {
            $edgarCron = new EdgarCron();
            $edgarCron->setAlias($alias);
        }

        $now = new \DateTime('now');
        $edgarCron->setQueued($now);
        $edgarCron->setStarted(null);
        $edgarCron->setEnded(null);
        $edgarCron->setStatus(self::STATUS_INIT);
        $this->getEntityManager()->persist($edgarCron);

        try {
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException $e) {
        }
    }

    /**
     * List cron command queued.
     *
     * @return EdgarCron[] cron commands queued
     */
    public function listQueued()
    {
        $query = $this->createQueryBuilder('c')
            ->where('c.started is NULL')
            ->getQuery();

        /** @var EdgarCron[] */
        return $query->getResult();
    }

    /**
     * Run cron command.
     *
     * @param EdgarCron $edgarCron cron command
     */
    public function run(EdgarCron $edgarCron)
    {
        $now = new \DateTime('now');
        $edgarCron->setStarted($now);
        $this->getEntityManager()->persist($edgarCron);

        try {
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException $e) {
        }
    }

    /**
     * End cron command.
     *
     * @param EdgarCron $edgarCron cron command
     * @param int $status cron command status
     */
    public function end(EdgarCron $edgarCron, int $status = self::STATUS_OK)
    {
        $now = new \DateTime('now');
        $edgarCron->setEnded($now);
        $edgarCron->setStatus($status);
        $this->getEntityManager()->persist($edgarCron);

        try {
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException $e) {
        }
    }

    /**
     * @return EdgarCron[] cron list
     */
    public function listCrons()
    {
        $query = $this->createQueryBuilder('c')
            ->getQuery();

        /** @var EdgarCron[] */
        return $query->getResult();
    }
}
