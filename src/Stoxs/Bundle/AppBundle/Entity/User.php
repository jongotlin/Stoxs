<?php

namespace Stoxs\Bundle\AppBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;

use Messy\MessagePartyInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser implements MessagePartyInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();

        $this->smses = new ArrayCollection();
    }

    /**
     * @var string $ssn
     *
     * @ORM\Column(name="ssn", type="string", length=11, nullable=true)
     */
    private $ssn;

    /**
     * @var string $phone_number
     *
     * @ORM\Column(name="phone_number", type="string", length=11, nullable=true)
     */
    private $phone_number;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    public function getNormalizedPhoneNumber()
    {
        return '+46'.substr($this->getPhoneNumber(), 1);
    }
  
    public function getName()
    {
        return $this->getUsername();
    }

    /**
     * Set ssn
     *
     * @param string $ssn
     */
    public function setSsn($ssn)
    {
        $this->ssn = $ssn;
    }

    /**
     * Get ssn
     *
     * @return string 
     */
    public function getSsn()
    {
        return $this->ssn;
    }
    
    /**
     * Set phone_number
     *
     * @param string $phone_number
     */
    public function setPhoneNumber($phone_number)
    {
        $this->phone_number = $phone_number;
    }

    /**
     * Get phone_number
     *
     * @return string 
     */
    public function getPhoneNumber()
    {
        return $this->phone_number;
    }
    
    public function isWinningAuction(Auction\Auction $auction)
    {
        $agent = $this->getAgentForAuction($auction);

        if (!$agent)
        {
            return false;
        }

        return $auction->getBidPositionForAgent($agent) !== null;
    }

    public function isInAuction(Auction\Auction $auction)
    {
        $agent = $this->getAgentForAuction($auction);

        return $agent !== null;
    }
    
    public function getAgentForAuction(Auction\Auction $auction)
    {
        foreach ($this->getAgents() as $agent)
        {
            if ($auction == $agent->getAuction())
            {
                return $agent;
            }
        }

        return null;
    }

    public function hasActiveBid()
    {
      return false;
    }

    protected $agent_in_notifier;

    protected $agent_out_notifier;

    public function setAgentInNotifier($callable)
    {
        $this->agent_in_notifier = $callable;
    }

    public function setAgentOutNotifier($callable)
    {
        $this->agent_out_notifier = $callable;
    }
    
    public function notifyAgentIn(Auction\BaseAgent $agent)
    {
        $notifier = $this->agent_in_notifier;
        call_user_func($notifier, $this, $agent);
    }
    
    public function notifyAgentOut(Auction\BaseAgent $agent)
    {
        $notifier = $this->agent_out_notifier;
        call_user_func($notifier, $this, $agent);
    }

    /**
     * @ORM\OneToMany(targetEntity="Sms", mappedBy="user") 
     */
    private $smses;
    
    public function getSmses()
    {
      return $this->smses;
    }

    /**
     * @ORM\OneToMany(targetEntity="Stoxs\Bundle\AppBundle\Entity\Auction\BaseAgent", mappedBy="user") 
     */
    private $agents;
    
    public function getAgents()
    {
      return $this->agents;
    }

    /**
     * Serializes the user.
     *
     * The serialized data have to contain the fields used by the equals method and the username.
     *
     * @return string
     */
    public function serialize()
    {
        return serialize(array(
            $this->password,
            $this->salt,
            $this->usernameCanonical,
            $this->username,
            $this->expired,
            $this->locked,
            $this->credentialsExpired,
            $this->enabled,
            $this->phone_number,
        ));
    }

    /**
     * Unserializes the user.
     *
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        list(
            $this->password,
            $this->salt,
            $this->usernameCanonical,
            $this->username,
            $this->expired,
            $this->locked,
            $this->credentialsExpired,
            $this->enabled,
            $this->phone_number,
        ) = unserialize($serialized);
    }
}