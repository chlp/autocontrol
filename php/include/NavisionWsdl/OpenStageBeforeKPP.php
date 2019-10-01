<?php

namespace nav;

class OpenStageBeforeKPP
{

    /**
     * @var string $sL_Barcode
     */
    protected $sL_Barcode = null;

    /**
     * @var string $errorText
     */
    protected $errorText = null;

    /**
     * @param string $sL_Barcode
     * @param string $errorText
     */
    public function __construct($sL_Barcode, $errorText)
    {
      $this->sL_Barcode = $sL_Barcode;
      $this->errorText = $errorText;
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
     * @return \nav\OpenStageBeforeKPP
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
     * @return \nav\OpenStageBeforeKPP
     */
    public function setErrorText($errorText)
    {
      $this->errorText = $errorText;
      return $this;
    }

}
