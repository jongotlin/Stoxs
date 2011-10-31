<?php

namespace Stoxs\Bundle\AppBundle\Auction;

/**
* 
*/
class Auctioneer
{
  protected $auction;
  protected $agents;

  public function __construct(Auction $auction)
  {
    $this->auction = $auction;
  }

  public function addAgent(AuctionAgentInterface $agent)
  {
    $this->agents[] = $agent;
    $this->processAgents();
  }

  public function processAgents()
  {
    do 
    {
      $altered_at = $this->auction->getAlteredAt();

      foreach ($this->agents as $agent) 
      {
        $agent->actOnAuction($this->auction);
      }
    } while ($altered_at != $this->auction->getAlteredAt());
  }
}