<?php

namespace Stoxs\Bundle\AppBundle\Entity\Auction;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="bid")
 */
class Bid
{
  /**
   * @ORM\Id 
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue
   */
  protected $id;

  /**
   * @ORM\ManyToOne(targetEntity="BaseAgent", inversedBy="bids", cascade={"persist"})
   */
  protected $agent;

  /**
   * @ORM\Column(type="integer") 
   */
  protected $amount;

  /**
   * @ORM\Column(type="float") 
   */  
  protected $added_at;

  /**
   * @ORM\Column(type="boolean") 
   */
  protected $is_active = true;

  /**
   * @ORM\ManyToOne(targetEntity="Auction", inversedBy="bids", cascade={"persist"})
   */
  protected $auction;

  public function __construct(AuctionAgentInterface $agent, $amount)
  {
    $agent->addBid($this);
    $this->agent = $agent;
    $this->amount = $amount;
  }

  public function setAuction(Auction $auction)
  {
    $this->auction = $auction;
  }

  public function getAgent()
  {
    return $this->agent;
  }

  public function getAmount()
  {
    return $this->amount;
  }

  public function setAddedAt($added_at)
  {
    $this->added_at = $added_at;
  }

  public function getAddedAt()
  {
    return $this->added_at;
  }

  public function deactivate()
  {
    $this->is_active = false;
  }

  public function isActive()
  {
    return $this->is_active;
  }

  public function compareTo(Bid $other_bid)
  {
    if ($this->getAmount() > $other_bid->getAmount())
    {
      return 1;
    }
    else if ($this->getAmount() < $other_bid->getAmount())
    {
      return -1;
    }
    else
    {
      if ($this->getAddedAt() < $other_bid->getAddedAt())
      {
        return 1;
      }
      else if ($this->getAddedAt() < $other_bid->getAddedAt())
      {
        return -1;
      }
      else
      {
        return 0;
      }
    }
  }
}