<?php

namespace Stoxs\Bundle\AppBundle\Entity\Auction;

/**
* 
*/
class NullBid extends Bid
{
  public function __construct()
  {
    $this->agent = null;
    $this->amount = 0;
  }
}