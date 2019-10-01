<?php

namespace nav;

class CheckSLCar_Result
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
     * @var string $directionOfMotion
     */
    protected $directionOfMotion = null;

    /**
     * @param boolean $return_value
     * @param string $errorText
     * @param string $directionOfMotion
     */
    public function __construct($return_value, $errorText, $directionOfMotion)
    {
      $this->return_value = $return_value;
      $this->errorText = $errorText;
      $this->directionOfMotion = $directionOfMotion;
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
     * @return \nav\CheckSLCar_Result
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
     * @return \nav\CheckSLCar_Result
     */
    public function setErrorText($errorText)
    {
      $this->errorText = $errorText;
      return $this;
    }

    /**
     * @return string
     */
    public function getDirectionOfMotion()
    {
      return $this->directionOfMotion;
    }

    /**
     * @param string $directionOfMotion
     * @return \nav\CheckSLCar_Result
     */
    public function setDirectionOfMotion($directionOfMotion)
    {
      $this->directionOfMotion = $directionOfMotion;
      return $this;
    }

}
