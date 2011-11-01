<?php

namespace Stoxs\Bundle\AppBundle\Service;

use Stoxs\Bundle\AppBundle\Queue\SmsQueue;
use Messy\MessageCenter;

/**
* 
*/
class SmsSenderService
{  
  protected $queue;
  protected $message_center;

  public function __construct(SmsQueue $queue, MessageCenter $message_center)
  {
    $this->queue = $queue;
    $this->message_center = $message_center;
  }

  public function run()
  {
    $message = $this->queue->dequeueBasicMessage();
    if ($message)
    {
      $this->message_center->send($message);      
    }
  }
}