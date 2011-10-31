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

  public function __toString()
  {
    return "Position maximizing agent with max-price ".$this->getMaxPrice()." min-pos ".$this->getMinPosition();
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

    $minimum_increment = $auction->getMinimumIncrement();

    $current_position = $auction->getBidPositionForAgent($this);

    $candidate_to_overtake = null;

    $index0 = $this->get0IndexedMinPosition();

    foreach ($bids as $position => $bid) 
    {
      if ($position <= $index0 && 
          ($current_position === null || $position < $current_position))
      {
        if ( $bid->getAmount()+$minimum_increment <= $this->max_price && 
             $bid->getAgent() != $this)
        {
          $candidate_to_overtake = $bid;
          break;
        }
      }
      else
      {
        break;
      }
    }

    // There's noone to overtake
    if ($candidate_to_overtake == null)
    {
      // Are we at an acceptable position?
      if ($current_position !== null && $current_position <= $index0)
      {
        // If so, we're happy just the way we are
        return;
      }
      else if ($auction->getAgentHasActiveBid($this))
      {
        // Otherwise, we need to pull out, if we're still in
        $auction->pullOut($this);
      }
    }
    else 
    {
      // Let's bid!
      $auction->placeBid(new Bid($this, $candidate_to_overtake->getAmount() + $minimum_increment));
    }
  }

/*  public function actOnAuction(Auction $auction)
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
        $current_bid = $auction->getActiveBidForAgent($this);

        if (!$current_bid || $current_bid->getAmount() < ($best_bid->getAmount() + $minimum_increment))
        {
          $auction->placeBid(new Bid($this, $best_bid->getAmount() + $minimum_increment));
        }
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
  }*/
}