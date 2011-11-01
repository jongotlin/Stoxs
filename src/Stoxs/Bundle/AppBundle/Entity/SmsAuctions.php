<?php

namespace Stoxs\Bundle\AppBundle\Entity;

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
}