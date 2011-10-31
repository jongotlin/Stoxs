<?php

namespace Stoxs\Bundle\AppBundle\Entity\Auction;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class PlaceMaximizingAgent extends BaseAgent implements AuctionAgentInterface
{
  public function __toString()
  {
    return "Position maximizing agent with max-price ".$this->getMaxPrice()." min-pos ".$this->getMinPosition();
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
}