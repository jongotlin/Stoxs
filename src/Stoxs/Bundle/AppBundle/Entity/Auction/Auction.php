<?php

namespace Stoxs\Bundle\AppBundle\Entity\Auction;

/**
* 
*/
class Auction
{
  protected $bids = array();
  protected $winning_bids = array();

  protected $winner_limit;

  protected $altered_at = 0;

  protected $minimum_increment;

  protected $agents;

  public function __construct($winner_limit, $minimum_increment)
  {
    $this->winner_limit = $winner_limit;
    $this->minimum_increment = $minimum_increment;

    $this->recalculateWinningBids();
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
      $altered_at = $this->getAlteredAt();

      foreach ($this->agents as $agent) 
      {
        $agent->actOnAuction($this);
      }
    } while ($altered_at != $this->getAlteredAt());
  }

  public function getMinimumIncrement()
  {
    return $this->minimum_increment;
  }

  public function getAllBids()
  {
    return $this->bids;
  }

  public function getWinningBids()
  {
    return $this->winning_bids;
  }

  protected function reCalculateWinningBids()
  {
    $bids = array_filter($this->bids, function(Bid $bid) 
    {
      return $bid->isActive();
    });

    usort($bids, function(Bid $a, Bid $b)
    {
      return $a->compareTo($b);
    });

    $this->winning_bids = array_slice(array_reverse($bids), 0, $this->winner_limit);

    $null_bid = new NullBid();

    for ($i=count($this->winning_bids); $i < $this->winner_limit; $i++) 
    { 
      $this->winning_bids[] = $null_bid;
    }
  }

  protected function deactivateBidsByAgent(AuctionAgentInterface $agent)
  {
    foreach ($this->bids as $bid) 
    {
      if ($bid->getAgent() == $agent)
      {
        $bid->deactivate();
      }
    }
  }

  public function getAgentHasActiveBid(AuctionAgentInterface $agent)
  {
    foreach ($this->bids as $bid) 
    {
      if ($bid->getAgent() == $agent && $bid->isActive())
      {
        return true;
      }
    }

    return false;
  }

  public function getActiveBidForAgent(AuctionAgentInterface $agent)
  {
    foreach ($this->bids as $bid) 
    {
      if ($bid->getAgent() == $agent && $bid->isActive())
      {
        return $bid;
      }
    }

    return null;
  }

  public function getBidPositionForAgent(AuctionAgentInterface $agent)
  {
    foreach ($this->winning_bids as $pos => $bid) 
    {
      if ($bid->getAgent() == $agent)
      {
        return $pos;
      }
    }

    return null;
  }

  protected function getAgentHash(AuctionAgentInterface $agent)
  {
    return spl_object_hash($agent);
  }

  public function placeBid(Bid $bid)
  {
    $this->altered_at = microtime(true);

    $this->deactivateBidsByAgent($bid->getAgent());

    $bid->setAddedAt(microtime(true));
    $this->bids[] = $bid;
    $this->reCalculateWinningBids();
  }

  public function pullOut(AuctionAgentInterface $agent)
  {
    $this->altered_at = microtime(true);

    $this->deactivateBidsByAgent($agent);

    $this->reCalculateWinningBids();
  }

  public function getAlteredAt()
  {
    return $this->altered_at;
  }
}