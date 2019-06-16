<?php

namespace ZendBaseModel\PortAdapter\Dispatch\Zend\ServiceLocator\Navigation;

use Zend\Navigation\Service\ConstructedNavigationFactory as ZendConstructedNavigationFactory;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZendBaseModel\PortAdapter\Dispatch\Zend\Navigation\Navigation;

/**
 * Class ConstructedNavigationFactory
 * @package ZendBaseModel\PortAdapter\Dispatch\Zend\ServiceLocator\Navigation
 */
class ConstructedNavigationFactory extends ZendConstructedNavigationFactory
{

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return \Zend\Navigation\Navigation
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $pages = $this->getPages($serviceLocator);
        $navigation = new Navigation($pages);
        $navigation->setTitle($this->getName());

        return $navigation;
    }

}
