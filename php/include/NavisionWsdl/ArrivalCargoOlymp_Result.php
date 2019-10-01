<?php

namespace nav;

class ArrivalCargoOlymp_Result
{

    /**
     * @var boolean $return_value
     */
    protected $return_value = null;

    /**
     * @var string $errorText
     */
    protected $errorText = null;

    /**
     * @param boolean $return_value
     * @param string $errorText
     */
    public function __construct($return_value, $errorText)
    {
      $this->return_value = $return_value;
      $this->errorText = $errorText;
    }

    /**
     * @return boolean
     */
    public function getReturn_value()
    {
      return $this->return_value;
    }

    /**
     * @param boolean $return_value
     * @return \nav\ArrivalCargoOlymp_Result
     */
    public function setReturn_value($return_value)
    {
      $this->return_value = $return_value;
      return $this;
    }

    /**
     * @return string
     */
    public function getErrorText()
    {
      return $this->errorText;
    }

    /**
     * @param string $errorText
     * @return \nav\ArrivalCargoOlymp_Result
     */
    public function setErrorText($errorText)
    {
      $this->errorText = $errorText;
      return $this;
    }

}
