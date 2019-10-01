<?php

namespace nav;

class GetLoadPlanWeightByAShNumber
{

    /**
     * @var string $sL_No
     */
    protected $sL_No = null;

    /**
     * @param string $sL_No
     */
    public function __construct($sL_No)
    {
      $this->sL_No = $sL_No;
    }

    /**
     * @return string
     */
    public function getSL_No()
    {
      return $this->sL_No;
    }

    /**
     * @param string $sL_No
     * @return \nav\GetLoadPlanWeightByAShNumber
     */
    public function setSL_No($sL_No)
    {
      $this->sL_No = $sL_No;
      return $this;
    }

}
