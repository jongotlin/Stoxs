<?php

namespace Stoxs\Bundle\AppBundle\Entity;


use Stoxs\Bundle\AppBundle\Entity\Auction\NullBid;

class SmsAuctions
{
  public $sms, $auctions;
  
  public function getBody()
  {
    return $this->sms->getBody();
  }
  
  public function setBody($body)
  {
    $this->sms->setBody($body);
  }
  
  public function updateUsers()
  {
    $bids = $this->auctions->getWinningBids();
    foreach ($bids as $bid) {
      if (!($bid instanceOf NullBid)) {
        $sms->addRecipientUser($big->getAgent()->addUser());
      }
    }
  }
}