<?php

namespace nav;

class ArrivalCargoOlymp
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
     * @var string $whBarcode
     */
    protected $whBarcode = null;

    /**
     * @var float $weight
     */
    protected $weight = null;

    /**
     * @var boolean $force
     */
    protected $force = null;

    /**
     * @var string $errorText
     */
    protected $errorText = null;

    /**
     * @param string $loginEmploe
     * @param string $passEmploe
     * @param string $whBarcode
     * @param float $weight
     * @param boolean $force
     * @param string $errorText
     */
    public function __construct($loginEmploe, $passEmploe, $whBarcode, $weight, $force, $errorText)
    {
      $this->loginEmploe = $loginEmploe;
      $this->passEmploe = $passEmploe;
      $this->whBarcode = $whBarcode;
      $this->weight = $weight;
      $this->force = $force;
      $this->errorText = $errorText;
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
     * @return \nav\ArrivalCargoOlymp
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
     * @return \nav\ArrivalCargoOlymp
     */
    public function setPassEmploe($passEmploe)
    {
      $this->passEmploe = $passEmploe;
      return $this;
    }

    /**
     * @return string
     */
    public function getWhBarcode()
    {
      return $this->whBarcode;
    }

    /**
     * @param string $whBarcode
     * @return \nav\ArrivalCargoOlymp
     */
    public function setWhBarcode($whBarcode)
    {
      $this->whBarcode = $whBarcode;
      return $this;
    }

    /**
     * @return float
     */
    public function getWeight()
    {
      return $this->weight;
    }

    /**
     * @param float $weight
     * @return \nav\ArrivalCargoOlymp
     */
    public function setWeight($weight)
    {
      $this->weight = $weight;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getForce()
    {
      return $this->force;
    }

    /**
     * @param boolean $force
     * @return \nav\ArrivalCargoOlymp
     */
    public function setForce($force)
    {
      $this->force = $force;
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
     * @return \nav\ArrivalCargoOlymp
     */
    public function setErrorText($errorText)
    {
      $this->errorText = $errorText;
      return $this;
    }

}
