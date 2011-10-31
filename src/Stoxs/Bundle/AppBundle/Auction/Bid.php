<?php

namespace Stoxs\Bundle\AppBundle\Auction;

/**
* 
*/
class Bid
{
  protected $agent;
  protected $amount;
  protected $added_at;
  protected $is_active = true;

  public function __construct(AuctionAgentInterface $agent, $amount)
  {
    $this->agent = $agent;
    $this->amount = $amount;
  }

  public function getAgent()
  {
    return $this->agent;
  }

  public function getAmount()
  {
    return $this->amount;
  }

  public function setAddedAt($added_at)
  {
    $this->added_at = $added_at;
  }

  public function getAddedAt()
  {
    return $this->added_at;
  }

  public function deactivate()
  {
    $this->is_active = false;
  }

  public function isActive()
  {
    return $this->is_active;
  }

  public function compareTo(Bid $other_bid)
  {
    if ($this->getAmount() > $other_bid->getAmount())
    {
      return 1;
    }
    else if ($this->getAmount() < $other_bid->getAmount())
    {
      return -1;
    }
    else
    {
      if ($this->getAddedAt() < $other_bid->getAddedAt())
      {
        return 1;
      }
      else if ($this->getAddedAt() < $other_bid->getAddedAt())
      {
        return -1;
      }
      else
      {
        return 0;
      }
    }
  }
}