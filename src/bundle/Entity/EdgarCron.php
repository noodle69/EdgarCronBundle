<?php

namespace Edgar\CronBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EdgarCron
 *
 * @ORM\Entity(repositoryClass="Edgar\Cron\Repository\EdgarCronRepository")
 * @ORM\Table(name="edgar_cron")
 */
class EdgarCron
{
    /**
     * @var string
     *
     * @ORM\Column(name="alias", type="string", length=255, nullable=false)
     * @ORM\Id
     */
    private $alias;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="queued", type="datetime", nullable=false)
     */
    private $queued = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="started", type="datetime", nullable=true)
     */
    private $started;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ended", type="datetime", nullable=true)
     */
    private $ended;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status = '0';

    /**
     * Get alias
     *
     * @return string
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * Set alias
     *
     * @param string $alias
     *
     * @return EdgarCron
     */
    public function setAlias(string $alias): self
    {
        $this->alias = $alias;
        return $this;
    }

    /**
     * Set queued
     *
     * @param \DateTime $queued
     *
     * @return EdgarCron
     */
    public function setQueued($queued): self
    {
        $this->queued = $queued;
        return $this;
    }

    /**
     * Get queued
     *
     * @return \DateTime
     */
    public function getQueued()
    {
        return $this->queued;
    }

    /**
     * Set started
     *
     * @param \DateTime $started
     *
     * @return EdgarCron
     */
    public function setStarted(?\DateTime $started): self
    {
        $this->started = $started;
        return $this;
    }

    /**
     * Get started
     *
     * @return \DateTime
     */
    public function getStarted(): \DateTime
    {
        return $this->started;
    }

    /**
     * Set ended
     *
     * @param \DateTime $ended
     *
     * @return EdgarCron
     */
    public function setEnded(?\DateTime $ended): self
    {
        $this->ended = $ended;
        return $this;
    }

    /**
     * Get ended
     *
     * @return \DateTime
     */
    public function getEnded(): \DateTime
    {
        return $this->ended;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return EdgarCron
     */
    public function setStatus(?int $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }
}
