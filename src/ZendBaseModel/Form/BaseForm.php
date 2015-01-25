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

    abstract protected function addElements();
}
