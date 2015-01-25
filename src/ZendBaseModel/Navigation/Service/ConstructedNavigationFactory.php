<?php

namespace ZendBaseModel\Navigation\Service;

use Zend\Navigation\Service\ConstructedNavigationFactory as ZendConstructedNavigationFactory;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZendBaseModel\Navigation\Navigation;

/**
 * Description of ConstructedNavigationFactory
 *
 * @author seyfer
 */
class ConstructedNavigationFactory extends ZendConstructedNavigationFactory
{

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return \Zend\Navigation\Navigation
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $pages      = $this->getPages($serviceLocator);
        $navigation = new Navigation($pages);
        $navigation->setTitle($this->getName());

        return $navigation;
    }

}
