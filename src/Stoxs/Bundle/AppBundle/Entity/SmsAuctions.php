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
  
  public function updateUsers()
  {
    $agents = $this->auctions->getAgents();
    foreach ($agents as $agent) {
      $sms->addRecipientUser($agent->addUser());
    }
  }
}