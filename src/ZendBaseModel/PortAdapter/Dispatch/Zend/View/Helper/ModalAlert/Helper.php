<?php
namespace ZendBaseModel\PortAdapter\Dispatch\Zend\View\Helper\ModalAlert;

use Zend\View\Helper\AbstractHelper;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver\TemplateMapResolver;

/**
 * Class Helper
 * @package ZendBaseModel\View\Helper\ModalAlert
 */
class Helper extends AbstractHelper
{
    /**
     * @var bool
     */
    public static $identifier = false;

    /**
     * @var array
     */
    protected $availableClasses = [
        'success',
        'info',
        'warning',
        'primary',
        'danger',
        'dark'
    ];

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

        $helperResolver->setMap(['modalAlert' => __DIR__ . '/view/modalAlert.phtml']);
        $helperRenderer->setResolver($helperResolver);

        return $helperRenderer->render('modalAlert', [
            'id'               => self::$identifier,
            'availableClasses' => $this->availableClasses
        ]);
    }

} 