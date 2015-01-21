<?php

namespace ZendBaseModel\Doctrine;

use Doctrine\ORM\EntityManager;

/**
 *
 * @author seyfer
 */
interface EntityManagerAwareInterface
{

    public function setEm(EntityManager $em);

    public function getEm();
}
