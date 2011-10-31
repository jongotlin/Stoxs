<?php

namespace Stoxs\Bundle\AppBundle\Entity\Auction;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class PriceMinimizingAgent extends BaseAgent implements AuctionAgentInterface
{
  public function __toString()
  {
    return "Price minimizing agent with max-price ".$this->getMaxPrice()." min-pos ".$this->getMinPosition();
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