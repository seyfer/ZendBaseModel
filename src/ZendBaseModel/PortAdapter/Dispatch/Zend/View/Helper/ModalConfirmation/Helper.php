<?php

namespace ZendBaseModel\PortAdapter\Dispatch\Zend\View\Helper\ModalConfirmation;

use Zend\View\Helper\AbstractHelper;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver\TemplateMapResolver;

/**
 * Class Helper
 * @package ZendBaseModel\View\Helper\ModalConfirmation
 */
class Helper extends AbstractHelper
{
    /**
     * @var bool
     */
    public static $identifier = false;

    /**
     * @return string
     * @throws \Exception
     */
    public function __invoke()
    {

        if (self::$identifier) {
            return '';
        }

        self::$identifier = uniqid('', false);

        $helperRenderer = new PhpRenderer();
        $helperResolver = new TemplateMapResolver();

        $helperResolver->setMap(['modalConfirmation' => __DIR__ . '/view/modalConfirmation.phtml']);
        $helperRenderer->setResolver($helperResolver);

        return $helperRenderer->render('modalConfirmation', ['id' => self::$identifier]);
    }

}
