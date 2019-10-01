<?php

namespace nav;

class GetWeightLimit
{

    /**
     * @var string $sL_Barcode
     */
    protected $sL_Barcode = null;

    /**
     * @param string $sL_Barcode
     */
    public function __construct($sL_Barcode)
    {
      $this->sL_Barcode = $sL_Barcode;
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
     * @return \nav\GetWeightLimit
     */
    public function setSL_Barcode($sL_Barcode)
    {
      $this->sL_Barcode = $sL_Barcode;
      return $this;
    }

}
