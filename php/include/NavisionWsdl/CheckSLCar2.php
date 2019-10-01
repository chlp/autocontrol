<?php

namespace nav;

class CheckSLCar2
{

    /**
     * @var string $loginEmploe
     */
    protected $loginEmploe = null;

    /**
     * @var string $passEmploe
     */
    protected $passEmploe = null;

    /**
     * @var string $sL_Barcode
     */
    protected $sL_Barcode = null;

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
     * @var float $planWeight
     */
    protected $planWeight = null;

    /**
     * @var string $autoType
     */
    protected $autoType = null;

    /**
     * @param string $loginEmploe
     * @param string $passEmploe
     * @param string $sL_Barcode
     * @param string $autoWeit
     * @param string $itemWeit
     * @param string $errorText
     * @param string $directionOfMotion
     * @param float $planWeight
     * @param string $autoType
     */
    public function __construct($loginEmploe, $passEmploe, $sL_Barcode, $autoWeit, $itemWeit, $errorText, $directionOfMotion, $planWeight, $autoType)
    {
      $this->loginEmploe = $loginEmploe;
      $this->passEmploe = $passEmploe;
      $this->sL_Barcode = $sL_Barcode;
      $this->autoWeit = $autoWeit;
      $this->itemWeit = $itemWeit;
      $this->errorText = $errorText;
      $this->directionOfMotion = $directionOfMotion;
      $this->planWeight = $planWeight;
      $this->autoType = $autoType;
    }

    /**
     * @return string
     */
    public function getLoginEmploe()
    {
      return $this->loginEmploe;
    }

    /**
     * @param string $loginEmploe
     * @return \nav\CheckSLCar2
     */
    public function setLoginEmploe($loginEmploe)
    {
      $this->loginEmploe = $loginEmploe;
      return $this;
    }

    /**
     * @return string
     */
    public function getPassEmploe()
    {
      return $this->passEmploe;
    }

    /**
     * @param string $passEmploe
     * @return \nav\CheckSLCar2
     */
    public function setPassEmploe($passEmploe)
    {
      $this->passEmploe = $passEmploe;
      return $this;
    }

    /**
     * @return string
     */
    public function getSL_Barcode()
    {
      return $this->sL_Barcode;
    }

    /**
     * @param string $sL_Barcode
     * @return \nav\CheckSLCar2
     */
    public function setSL_Barcode($sL_Barcode)
    {
      $this->sL_Barcode = $sL_Barcode;
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
     * @return \nav\CheckSLCar2
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
     * @return \nav\CheckSLCar2
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
     * @return \nav\CheckSLCar2
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
     * @return \nav\CheckSLCar2
     */
    public function setDirectionOfMotion($directionOfMotion)
    {
      $this->directionOfMotion = $directionOfMotion;
      return $this;
    }

    /**
     * @return float
     */
    public function getPlanWeight()
    {
      return $this->planWeight;
    }

    /**
     * @param float $planWeight
     * @return \nav\CheckSLCar2
     */
    public function setPlanWeight($planWeight)
    {
      $this->planWeight = $planWeight;
      return $this;
    }

    /**
     * @return string
     */
    public function getAutoType()
    {
      return $this->autoType;
    }

    /**
     * @param string $autoType
     * @return \nav\CheckSLCar2
     */
    public function setAutoType($autoType)
    {
      $this->autoType = $autoType;
      return $this;
    }

}
