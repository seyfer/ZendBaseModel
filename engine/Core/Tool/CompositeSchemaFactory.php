<?php
namespace Core\Tool;

use Core\Tool\CompositeSchema as ToolCompositeSchema;
use Zend\Debug\Debug;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class CompositeSchemaFactory
 * @package Core\Tool
 */
class CompositeSchemaFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return ToolCompositeSchema|mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        try {
            $selectedEntityManagers = [];
            $config                 = $serviceLocator->get('Config');

            $availableEntityManagers = array_keys($config['doctrine']['entitymanager']);

            foreach ($availableEntityManagers as $entityManager) {
                if (strstr($entityManager, "sql")) {
                    $selectedEntityManagers[] = $serviceLocator->get('doctrine.entitymanager.' . $entityManager);
                }
            }
        } catch (\Exception $e) {
            Debug::dump($e->getMessage());

            if ($e->getPrevious()) {
                Debug::dump($e->getPrevious()->getMessage());
            }
        }

        return new ToolCompositeSchema($selectedEntityManagers);
    }
}