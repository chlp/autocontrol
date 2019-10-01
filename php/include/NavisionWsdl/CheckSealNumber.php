<?php

namespace nav;

class CheckSealNumber
{

    /**
     * @var string $plombaNo
     */
    protected $plombaNo = null;

    /**
     * @param string $plombaNo
     */
    public function __construct($plombaNo)
    {
      $this->plombaNo = $plombaNo;
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
     * @return \nav\CheckSealNumber
     */
    public function setPlombaNo($plombaNo)
    {
      $this->plombaNo = $plombaNo;
      return $this;
    }

}
