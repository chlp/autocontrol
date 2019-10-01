<?php

namespace nav;

class CheckSealNumber_Result
{

    /**
     * @var boolean $return_value
     */
    protected $return_value = null;

    /**
     * @param boolean $return_value
     */
    public function __construct($return_value)
    {
      $this->return_value = $return_value;
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
     * @return \nav\CheckSealNumber_Result
     */
    public function setReturn_value($return_value)
    {
      $this->return_value = $return_value;
      return $this;
    }

}
