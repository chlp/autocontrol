<?php

namespace nav;

class CheckDepatureCar1
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
     * @var float $weight
     */
    protected $weight = null;

    /**
     * @var string $tIRNO
     */
    protected $tIRNO = null;

    /**
     * @var string $plombaNo
     */
    protected $plombaNo = null;

    /**
     * @var string $errorText
     */
    protected $errorText = null;

    /**
     * @param string $loginEmploe
     * @param string $passEmploe
     * @param string $sL_Barcode
     * @param float $weight
     * @param string $tIRNO
     * @param string $plombaNo
     * @param string $errorText
     */
    public function __construct($loginEmploe, $passEmploe, $sL_Barcode, $weight, $tIRNO, $plombaNo, $errorText)
    {
      $this->loginEmploe = $loginEmploe;
      $this->passEmploe = $passEmploe;
      $this->sL_Barcode = $sL_Barcode;
      $this->weight = $weight;
      $this->tIRNO = $tIRNO;
      $this->plombaNo = $plombaNo;
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
     * @return \nav\CheckDepatureCar1
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
     * @return \nav\CheckDepatureCar1
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
     * @return \nav\CheckDepatureCar1
     */
    public function setSL_Barcode($sL_Barcode)
    {
      $this->sL_Barcode = $sL_Barcode;
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
     * @return \nav\CheckDepatureCar1
     */
    public function setWeight($weight)
    {
      $this->weight = $weight;
      return $this;
    }

    /**
     * @return string
     */
    public function getTIRNO()
    {
      return $this->tIRNO;
    }

    /**
     * @param string $tIRNO
     * @return \nav\CheckDepatureCar1
     */
    public function setTIRNO($tIRNO)
    {
      $this->tIRNO = $tIRNO;
      return $this;
    }

    /**
     * @return string
     */
    public function getPlombaNo()
    {
      return $this->plombaNo;
    }

    /**
     * @param string $plombaNo
     * @return \nav\CheckDepatureCar1
     */
    public function setPlombaNo($plombaNo)
    {
      $this->plombaNo = $plombaNo;
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
     * @return \nav\CheckDepatureCar1
     */
    public function setErrorText($errorText)
    {
      $this->errorText = $errorText;
      return $this;
    }

}
