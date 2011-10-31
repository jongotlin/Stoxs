<?php

namespace Stoxs\Bundle\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Messy\MessageInterface;

/**
 * Stoxs\Bundle\AppBundle\Entity\Sms
 *
 * @ORM\Table(name="sms")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Sms implements MessageInterface
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var text $body
     *
     * @ORM\Column(name="body", type="text")
     */
    private $body;

    /**
     * @var datetime $created_at
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="smses")
     */
    private $user;

    private $recipient_users = array();

    public function setUser(User $user)
    {
      $this->user = $user;
    }
    
    public function getUser()
    {
      return $this->user;
    }

    public function addRecipientUser(User $user)
    {
        $this->recipient_users[] = $user;
    }

    public function getRecipientUsers()
    {
        return $this->recipient_users;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set body
     *
     * @param text $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * Get body
     *
     * @return text 
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set created_at
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
    }

    /**
     * Get created_at
     *
     * @return datetime 
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }
    
    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->created_at = new \DateTime();
    }

    // ------- MessageInterface --------

    public function getMessageBody()
    {
        return $this->getBody();
    }

    public function getSender()
    {
        return $this->getUser();
    }

    public function getRecipients()
    {
        return $this->getRecipientUsers();
    }
}