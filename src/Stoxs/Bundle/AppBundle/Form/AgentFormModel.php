<?php

namespace Stoxs\Bundle\AppBundle\Form;

use Stoxs\Bundle\AppBundle\Entity\Auction\PlaceMaximizingAgent;
use Stoxs\Bundle\AppBundle\Entity\Auction\PriceMinimizingAgent;

class AgentFormModel
{
  
  public $max_price, $min_position, $discr;
  
  public $acuntion, $agent, $user;

  public function createAgent()
  {
    if ($this->discr == 'placemax') {
      $agent = new PlaceMaximizingAgent($this->max_price, $this->min_position);
    } else {
      $agent = new PriceMinimizingAgent($this->max_price, $this->min_position);
    }
    $agent->setAuction($this->auction);
    $agent->setUser($this->user);
    return $agent;
  }
  
}