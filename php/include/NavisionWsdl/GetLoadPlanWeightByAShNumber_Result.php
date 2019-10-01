<?php

namespace nav;

class GetLoadPlanWeightByAShNumber_Result
{

    /**
     * @var float $return_value
     */
    protected $return_value = null;

    /**
     * @param float $return_value
     */
    public function __construct($return_value)
    {
      $this->return_value = $return_value;
    }

    /**
     * @return float
     */
    public function getReturn_value()
    {
      return $this->return_value;
    }

    /**
     * @param float $return_value
     * @return \nav\GetLoadPlanWeightByAShNumber_Result
     */
    public function setReturn_value($return_value)
    {
      $this->return_value = $return_value;
      return $this;
    }

}
