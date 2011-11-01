<?php

namespace Stoxs\Bundle\AppBundle\Queue;

use Stoxs\Bundle\AppBundle\Entity\Sms;

use Messy\BasicMessage;

use Pheanstalk\Pheanstalk;

/**
* 
*/
class SmsQueue
{
  const TUBE_NAME = "sms_message_tube";

  protected $pheanstalk;
  
  public function __construct(Pheanstalk $pheanstalk)
  {
    $this->pheanstalk = $pheanstalk;
  }

  public function enqueueSms(Sms $sms)
  {
    foreach ($sms->getRecipientUsers() as $recipient) 
    {
      $message = new BasicMessage();
      $message->setMessageBody($sms->getMessageBody());
      $message->setSender($sms->getSender());
      $message->addRecipient($recipient);

      $this->enqueueBasicMessage($message);
    }
  }

  protected function enqueueBasicMessage(BasicMessage $message)
  {
    $this->pheanstalk
      ->useTube(self::TUBE_NAME)
      ->put(serialize($message));
  }

  public function dequeueBasicMessage()
  {
    $job = $this->pheanstalk
      ->watch(self::TUBE_NAME)
      ->ignore('default')
      ->reserve();

    return unserialize($job->getData());
  }
}