<?php

namespace Stoxs\Bundle\AppBundle\Auction;

interface AuctionAgentInterface
{
  /**
   * @return integer|null
   */
  public function actOnAuction(Auction $auction);
}