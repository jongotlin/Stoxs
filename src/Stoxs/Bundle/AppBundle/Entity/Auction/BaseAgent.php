<?php

namespace Stoxs\Bundle\AppBundle\Entity\Auction;

use Doctrine\ORM\Mapping as ORM;
use Stoxs\Bundle\AppBundle\Entity\User;
use Stoxs\Bundle\AppBundle\Entity\Auction\AgentRepository;

/**
 * @ORM\Entity
 * @ORM\Table(name="agent")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"placemax" = "PlaceMaximizingAgent", "pricemin" = "PriceMinimizingAgent"})
 * @ORM\Entity(repositoryClass="Stoxs\Bundle\AppBundle\Entity\Auction\AgentRepository")
 */
abstract class BaseAgent
{
  /**
   * @ORM\Id 
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue
   */
  protected $id;

  /**
   * @ORM\Column(type="integer") 
   */
  protected $max_price;
  
  /**
   * @ORM\Column(type="integer") 
   */
  protected $min_position;

  /**
   * @ORM\ManyToOne(targetEntity="Auction", inversedBy="agents", cascade={"persist"})
   */
  protected $auction;

  /**
   * @ORM\OneToMany(targetEntity="Bid", mappedBy="agent", cascade={"persist"})
   */
  protected $bids = array();

  /**
   * @ORM\ManyToOne(targetEntity="Stoxs\Bundle\AppBundle\Entity\User", inversedBy="agents")
   */
  private $user;

  public function setUser(User $user)
  {
    $this->user = $user;
  }
  
  public function getUser()
  {
    return $this->user;
  }


  public function __construct($max_price, $min_position)
  {
    $this->max_price = $max_price;
    $this->min_position = $min_position;
  }

  public function addBid(Bid $bid)
  {
    $this->bids[] = $bid;
  }

  public function setAuction(Auction $auction)
  {
    $this->auction = $auction;
  }

  public function getMaxPrice()
  {
    return $this->max_price;
  }
  
  public function setMaxPrice($max_price)
  {
    $this->max_price = $max_price;
  }

  public function getMinPosition()
  {
    return $this->min_position;
  }
  
  public function setMinPosition($min_position)
  {
    $this->min_position = $min_position;
  }

  protected function get0IndexedMinPosition()
  {
    return $this->min_position - 1;
  }
  
  public function getAuction()
  {
    return $this->auction;
  }
  
  public function getId()
  {
    return $this->id;
  }

  public function notifyUserOut()
  {
    if ($this->user)
    {
      $this->user->notifyAgentOut($this);      
    }
  }

  public function notifyUserIn()
  {
    if ($this->user)
    {
      $this->user->notifyAgentIn($this);      
    }
  }
}