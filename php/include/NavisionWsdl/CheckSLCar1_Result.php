<?php

namespace nav;

class CheckSLCar1_Result
{

    /**
     * @var boolean $return_value
     */
    protected $return_value = null;

    /**
     * @var string $autoWeit
     */
    protected $autoWeit = null;

    /**
     * @var string $itemWeit
     */
    protected $itemWeit = null;

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
     * @param string $autoWeit
     * @param string $itemWeit
     * @param string $errorText
     * @param string $directionOfMotion
     */
    public function __construct($return_value, $autoWeit, $itemWeit, $errorText, $directionOfMotion)
    {
      $this->return_value = $return_value;
      $this->autoWeit = $autoWeit;
      $this->itemWeit = $itemWeit;
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
     * @return \nav\CheckSLCar1_Result
     */
    public function setReturn_value($return_value)
    {
      $this->return_value = $return_value;
      return $this;
    }

    /**
     * @return string
     */
    public function getAutoWeit()
    {
      return $this->autoWeit;
    }

    /**
     * @param string $autoWeit
     * @return \nav\CheckSLCar1_Result
     */
    public function setAutoWeit($autoWeit)
    {
      $this->autoWeit = $autoWeit;
      return $this;
    }

    /**
     * @return string
     */
    public function getItemWeit()
    {
      return $this->itemWeit;
    }

    /**
     * @param string $itemWeit
     * @return \nav\CheckSLCar1_Result
     */
    public function setItemWeit($itemWeit)
    {
      $this->itemWeit = $itemWeit;
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
     * @return \nav\CheckSLCar1_Result
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
     * @return \nav\CheckSLCar1_Result
     */
    public function setDirectionOfMotion($directionOfMotion)
    {
      $this->directionOfMotion = $directionOfMotion;
      return $this;
    }

}
