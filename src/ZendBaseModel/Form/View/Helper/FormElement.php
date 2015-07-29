<?php

namespace ZendBaseModel\Form\View\Helper;

use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\FormElement as BaseFormElementView;
use ZendBaseModel\Form\Element;

/**
 * Description of FormElement
 *
 * @author seyfer
 */
class FormElement extends BaseFormElementView
{

    /**
     * @param ElementInterface $element
     * @return string
     */
    public function render(ElementInterface $element)
    {
        $renderer = $this->getView();
        if (!method_exists($renderer, 'plugin')) {
            // Bail early if renderer is not pluggable
            return '';
        }

        if ($element instanceof Element\DateTimePicker) {
            $helper = $renderer->plugin('FormDateTimePicker');

            return $helper($element);
        }

        return parent::render($element);
    }

}
