<?php

namespace ZendBaseModel\Form;

use Zend\Form\Element;
use Zend\Form\Form;

/**
 * Description of BaseForm
 *
 * @author seyfer
 */
abstract class BaseForm extends Form
{
    public function __construct($name)
    {
        parent::__construct($name);

        $this->addElements();
    }

    abstract protected function addElements();

    protected function addTextElement($name)
    {
        $element = new Element\Text($name);
        $element->setLabel(ucfirst($name));
        $this->add($element);
    }
}
