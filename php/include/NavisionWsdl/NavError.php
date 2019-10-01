<?php

namespace nav;

class NavError
{
    private $errorText;

    /**
     * NavError constructor.
     * @param $errorText
     */
    public function __construct($errorText)
    {
        $this->errorText = (string)$errorText;
    }

    /**
     * @return bool
     */
    public function getReturn_value()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getErrorText()
    {
        return $this->errorText;
    }
}
