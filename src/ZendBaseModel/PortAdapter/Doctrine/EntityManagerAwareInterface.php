<?php

namespace ZendBaseModel\PortAdapter\Doctrine;

use Doctrine\ORM\EntityManager;

/**
 *
 * @author seyfer
 */
interface EntityManagerAwareInterface
{
    /**
     * @param EntityManager $em
     * @return mixed
     */
    public function setEm(EntityManager $em);

    public function getEm();
}
