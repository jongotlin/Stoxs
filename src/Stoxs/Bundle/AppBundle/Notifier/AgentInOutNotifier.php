<?php

namespace Stoxs\Bundle\AppBundle\Notifier;

use Stoxs\Bundle\AppBundle\Entity\User;
use Stoxs\Bundle\AppBundle\Entity\Auction\BaseAgent;

use Doctrine\ORM\Event\LifecycleEventArgs;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

class AgentInOutNotifier
{
  protected $swiftmailer;
  protected $container;

  public function __construct(\Swift_Mailer $swiftmailer, $container)
  {
    $this->swiftmailer = $swiftmailer;
    $this->container = $container;
  }

  public function postLoad(LifecycleEventArgs $e)
  {
    $entity = $e->getEntity();
    if ($entity instanceof User)
    {
      $this->setNotifiersOnUser($entity);
    }
  }

  public function setNotifiersOnUser(User $user)
  {
    $user->setAgentInNotifier(array($this, 'agentIn'));
    $user->setAgentOutNotifier(array($this, 'agentOut'));
  }

  public function agentIn(User $user, BaseAgent $agent)
  {
    $email = $user->getEmail();

    $message = \Swift_Message::newInstance()
        ->setSubject('Din budagent är inne i auktionen')
        ->setFrom('info@stoxs.se')
        ->setTo($email)
        ->setBody($this->container->get('templating')->render('StoxsAppBundle:Email:agentIn.txt.twig', array('user' => $user, 'agent' => $agent)))
    ;
    $this->swiftmailer->send($message);
  }

  public function agentOut(User $user, Agent $agent)
  {
    $email = $user->getEmail();

    $message = \Swift_Message::newInstance()
        ->setSubject('Din budagent är ute ur auktionen')
        ->setFrom('info@stoxs.se')
        ->setTo($email)
        ->setBody($this->container->get('templating')->render('StoxsAppBundle:Email:agentOut.txt.twig', array('user' => $user, 'agent' => $agent)))
    ;
    $this->swiftmailer->send($message);
  }
}