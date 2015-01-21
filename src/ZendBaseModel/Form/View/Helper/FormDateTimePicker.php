<?php

namespace ZendBaseModel\Form\View\Helper;

use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\AbstractHelper;

/**
 * Description of FormDateTimePicker
 *
 * @author seyfer
 */
class FormDateTimePicker extends AbstractHelper
{

    protected $template = 'model/form-element/datetimepicker';

    public function __invoke(ElementInterface $element)
    {
        $rendered = $this->renderTpl($element);

        //        $rendered = $this->renderInCode($element);

        return $rendered;
    }

    /**
     * @param ElementInterface $element
     * @return string
     */
    private function renderTpl(ElementInterface $element)
    {
        return $this->getView()->render($this->template, [
            'element' => $element
        ]);
    }

    /**
     * @param ElementInterface $element
     * @return string
     */
    private function renderInCode(ElementInterface $element)
    {
        $rendered = '';

        $rendered .= "<div class='form-group'>";

        $rendered .= "<label>" . $element->getLabel() . "</label>";
        $rendered .= "<div class='input-group date datetimepicker' data-date-format='YYYY-MM-DD'>";

        $rendered .= "<input type='text' name='" . $element->getName() .
                     "' class='form-control input-group date' size='10' value='" . $element->getValue() . "'>";
        $rendered .= "<span class='input-group-addon'><span class='glyphicon glyphicon-time'></span></span>";

        $rendered .= "</div>";
        $rendered .= "</div>";

        return $rendered;
    }

}
