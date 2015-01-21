<?php

namespace ZendBaseModel\Traits;

/**
 * Description of DebugMode
 *
 * @author seyfer
 */
trait DebugMode
{

    /**
     *
     * @var boolean
     */
    protected $debugMode = FALSE;

    public function getDebugMode()
    {
        return $this->debugMode;
    }

    public function setDebugMode($debugMode)
    {
        $this->debugMode = $debugMode;
    }

    public function debugModeOn()
    {
        $this->setDebugMode(TRUE);
    }

    public function debugModeOff()
    {
        $this->setDebugMode(FALSE);
    }

    /**
     * @return bool
     */
    public function isDebugMode()
    {
        return $this->getDebugMode() === TRUE;
    }

}
