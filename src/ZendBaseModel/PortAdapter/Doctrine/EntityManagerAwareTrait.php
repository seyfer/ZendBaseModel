<?php

namespace ZendBaseModel\PortAdapter\Doctrine;

use Doctrine\ORM\EntityManager;

/**
 * Description of EntityManagerAwareTrait
 *
 * @author seyfer
 */
trait EntityManagerAwareTrait
{

    /**
     *
     * @var EntityManager
     */
    protected $em;

    /**
     *
     * @return EntityManager
     */
    public function getEm()
    {
        return $this->em;
    }

    /**
     * @param EntityManager $em
     */
    public function setEm(EntityManager $em)
    {
        $this->em = $em;
    }

}
