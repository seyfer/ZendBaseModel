<?php

namespace ZendBaseModel\PortAdapter\Dispatch\Zend\Navigation;

use Zend\Navigation\Navigation as ZendNavigation;

/**
 * Description of Navigation
 *
 * @author seyfer
 */
class Navigation extends ZendNavigation
{

    protected $title;

    function getTitle()
    {
        return $this->title;
    }

    function setTitle($title)
    {
        $this->title = $title;
    }

}
