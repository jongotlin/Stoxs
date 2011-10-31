<?php

namespace Stoxs\Bundle\AppBundle\Auction;

/**
* 
*/
class PlaceMaximizingAgent implements AuctionAgentInterface
{
  protected $max_price;
  protected $min_position;

  public function __construct($max_price, $min_position)
  {
    $this->max_price = $max_price;
    $this->min_position = $min_position;
  }

  public function getMaxPrice()
  {
    return $this->max_price;
  }

  public function getMinPosition()
  {
    return $this->min_position;
  }

  protected function get0IndexedMinPosition()
  {
    return $this->min_position - 1;
  }

  public function actOnAuction(Auction $auction)
  {
    $bids = $auction->getWinningBids();

    $best_position = null;
    $best_bid = null;

    $minimum_increment = $auction->getMinimumIncrement();

    foreach ($bids as $position => $bid) 
    {
      if ($bid->getAmount()+$minimum_increment <= $this->max_price)
      {
        $best_position = $position;
        $best_bid = $bid;
        break;
      }
    }

    if (!$best_bid)
    {
      if ($auction->getAgentHasActiveBid($this))
      {
        $auction->pullOut($this);
      }

      return;
    }

    if ($best_bid->getAgent() != $this)
    {
      if ($best_position <= $this->get0IndexedMinPosition())
      {
        $auction->placeBid(new Bid($this, $best_bid->getAmount() + $minimum_increment));
      }
      else if ($auction->getAgentHasActiveBid($this))
      {
        $auction->pullOut($this);
      }
    }
    else
    {
      // Are we still in a desirable position?
      if ($best_position > $this->get0IndexedMinPosition())
      {
        $auction->pullOut($this);
      }

    }
  }
}