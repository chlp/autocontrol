<?php

namespace nav;

class CheckSLCar
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
     * @var string $errorText
     */
    protected $errorText = null;

    /**
     * @var string $directionOfMotion
     */
    protected $directionOfMotion = null;

    /**
     * @param string $loginEmploe
     * @param string $passEmploe
     * @param string $sL_Barcode
     * @param string $errorText
     * @param string $directionOfMotion
     */
    public function __construct($loginEmploe, $passEmploe, $sL_Barcode, $errorText, $directionOfMotion)
    {
      $this->loginEmploe = $loginEmploe;
      $this->passEmploe = $passEmploe;
      $this->sL_Barcode = $sL_Barcode;
      $this->errorText = $errorText;
      $this->directionOfMotion = $directionOfMotion;
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
     * @return \nav\CheckSLCar
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
     * @return \nav\CheckSLCar
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
     * @return \nav\CheckSLCar
     */
    public function setSL_Barcode($sL_Barcode)
    {
      $this->sL_Barcode = $sL_Barcode;
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
     * @return \nav\CheckSLCar
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
     * @return \nav\CheckSLCar
     */
    public function setDirectionOfMotion($directionOfMotion)
    {
      $this->directionOfMotion = $directionOfMotion;
      return $this;
    }

}
