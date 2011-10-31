<?php

namespace Stoxs\Bundle\AppBundle\Entity\Auction;

/**
* 
*/
class PriceMinimizingAgent implements AuctionAgentInterface
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
    return "Price minimizing agent with max-price ".$this->getMaxPrice()." min-pos ".$this->getMinPosition();
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

    if ($current_position !== null && $current_position <= $this->get0IndexedMinPosition())
    {
      return;
    }

    $wanted_bid = $bids[$this->get0IndexedMinPosition()];

    if ($wanted_bid->getAmount() + $minimum_increment <= $this->getMaxPrice())
    {
      $auction->placeBid(new Bid($this, $wanted_bid->getAmount() + $minimum_increment));
    }
    else
    {
      if ($auction->getAgentHasActiveBid($this))
      {
        $auction->pullOut($this);
      }
    }
  }
}