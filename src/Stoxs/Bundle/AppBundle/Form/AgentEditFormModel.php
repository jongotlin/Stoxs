<?php

namespace Stoxs\Bundle\AppBundle\Form;

use Stoxs\Bundle\AppBundle\Entity\Auction\PlaceMaximizingAgent;
use Stoxs\Bundle\AppBundle\Entity\Auction\PriceMinimizingAgent;

class AgentEditFormModel
{
  
  public $agent;
  
  public function getMaxPrice()
  {
    return $this->agent->getMaxPrice();
  }
  
  public function setMaxPrice($max_price)
  {
    $this->agent->setMaxPrice($max_price);
  }
  
  public function getMinPosition()
  {
    return $this->agent->getMinPosition();
  }
  
  public function setMinPosition($min_position)
  {
    $this->agent->setMinPosition($min_position);
  }
  /*
  public function getAgent()
  {
    //$this->agent->setMaxPrice($this->max_price);
    //$this->agent->setMinPosition($this->min_position);
    return $this->agent;
  }
  */
  
}