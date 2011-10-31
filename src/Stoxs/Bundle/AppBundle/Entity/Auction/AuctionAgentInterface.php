<?php

namespace Stoxs\Bundle\AppBundle\Entity\Auction;

interface AuctionAgentInterface
{
  /**
   * @return integer|null
   */
  public function actOnAuction(Auction $auction);
}