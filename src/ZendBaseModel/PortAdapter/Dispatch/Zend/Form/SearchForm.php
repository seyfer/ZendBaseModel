<?php

namespace ZendBaseModel\PortAdapter\Dispatch\Zend\Form;

use Zend\Form\Element;

/**
 * Description of SearchForm
 *
 * @author seyfer
 */
class SearchForm extends BaseForm
{

    public function __construct()
    {
        parent::__construct(__CLASS__);

        $this->addElements();
    }

    protected function addElements()
    {
        $this->setAttribute("method", "post");
        $this->setAttribute("id", __CLASS__);

        $pageNum = new Element\Text("pagenum");
        $pageNum->setValue(5);
        $pageNum->setLabel("На страницу");
        $pageNum->setAttribute("size", 5);
        $this->add($pageNum);

        $submit = new Element\Submit("submit");
        $submit->setValue("Применить");
        $submit->setAttributes([
            "class" => "btn btn-default",
        ]);
        $this->add($submit);
    }

}
