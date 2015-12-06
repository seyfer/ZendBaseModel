<?php
namespace Core\Controller;

use Core\Tool\CompositeSchema;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class SchemaController
 * @package Core\Controller
 */
class SchemaController extends AbstractActionController
{
    public function generateDiffAction()
    {
        $withDrop = (bool)$this->getRequest()->getParam('withDrop', false);
        $withKey  = (bool)$this->getRequest()->getParam('withKey', false);

        /** @var CompositeSchema $service */
        $service = $this->getServiceLocator()->get('core.tool.compositeschema');
        $output  = implode(";\n\n", $service->generateSchemaDiff($withDrop, $withKey));
        if (empty($output)) {
            $output = 'No changes detected in your mapping information.';
        } else {
            $output .= ";";

            $dir        = __DIR__ . "/../../../data/sql";
            $dateString = (new \DateTime())->format("Y-m-d.h-i-s");
            $file       = $dateString . ".sql";
            $filePath   = $dir . DIRECTORY_SEPARATOR . $file;
            file_put_contents($filePath, $output);

            echo "file " . $file . " created with output: \n\n";
        }

        return $output . "\n";
    }
}
