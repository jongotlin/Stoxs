<?php

namespace Stoxs\Bundle\AppBundle\Entity\Auction;

use Doctrine\ORM\Mapping as ORM;
use Stoxs\Bundle\AppBundle\Entity\Auction\AuctionRepository;

/**
 * @ORM\Entity
 * @ORM\Table(name="auction")
 * @ORM\Entity(repositoryClass="Stoxs\Bundle\AppBundle\Entity\Auction\AuctionRepository")
 */
class Auction
{
  /**
   * @ORM\Id 
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue
   */
  protected $id;
  
  /**
   * @ORM\Column(type="datetime")
   */
  protected $stop_time;

  /**
   * @ORM\OneToMany(targetEntity="Bid", mappedBy="auction", cascade={"persist"})
   */
  protected $bids = array();

  protected $winning_bids = array();
  protected $winning_bids_calculated = false;

  /**
   * @ORM\Column(type="integer") 
   */
  protected $winner_limit;

  protected $altered_at = 0;

  /**
   * @ORM\Column(type="integer") 
   */
  protected $minimum_increment;

  /**
   * @ORM\OneToMany(targetEntity="BaseAgent", mappedBy="auction", cascade={"persist"})
   */
  protected $agents;

  public function __construct($winner_limit, $minimum_increment)
  {
    $this->winner_limit = $winner_limit;
    $this->minimum_increment = $minimum_increment;

    $this->recalculateWinningBids();
  }

  public function addAgent(AuctionAgentInterface $agent)
  {
    $this->agents[] = $agent;
    $agent->setAuction($this);
    $this->processAgents();
  }

  public function processAgents()
  {
    $before_out = $this->getOutAgents();

    do 
    {
      $altered_at = $this->getAlteredAt();

      foreach ($this->agents as $agent) 
      {
        $agent->actOnAuction($this);
      }
    } while ($altered_at != $this->getAlteredAt());

    $after_out = $this->getOutAgents();

    $new_out = array_diff($after_out, $before_out);
    $new_in = array_diff($before_out, $after_out);

    foreach ($new_out as $agent) 
    {
      $agent->notifyUserOut();
    }

    foreach ($new_in as $agent)
    {
      $agent->notifyUserIn();
    }
  }

  public function getOutAgents()
  {
    $agents = $this->agents;
    if (!is_array($agents))
    {
      $agents = $agents->toArray();
    }

    return array_diff($agents, $this->getInAgents());
  }

  public function getInAgents()
  {
    $in_agents = array();
    foreach ($this->getWinningBids() as $bid)
    {
      if (!($bid instanceof NullBid))
      {
        $in_agents[] = $bid->getAgent();
      }
    }

    return $in_agents;
  }

  public function getMinimumIncrement()
  {
    return $this->minimum_increment;
  }

  public function getAllBids()
  {
    return $this->bids;
  }

  public function getWinningBids()
  {
    if (!$this->winning_bids_calculated)
    {
      $this->reCalculateWinningBids();
    }
    return $this->winning_bids;
  }

  protected function reCalculateWinningBids()
  {
    $regular_bids = $this->bids;
    if (!is_array($regular_bids))
    {
      $regular_bids = $regular_bids->toArray();
    }

    $bids = array_filter($regular_bids, function(Bid $bid) 
    {
      return $bid->isActive();
    });

    usort($bids, function(Bid $a, Bid $b)
    {
      return $a->compareTo($b);
    });

    $this->winning_bids = array_slice(array_reverse($bids), 0, $this->winner_limit);

    $null_bid = new NullBid();

    for ($i=count($this->winning_bids); $i < $this->winner_limit; $i++) 
    { 
      $this->winning_bids[] = $null_bid;
    }

    $this->winning_bids_calculated = true;
  }

  protected function deactivateBidsByAgent(AuctionAgentInterface $agent)
  {
    foreach ($this->bids as $bid) 
    {
      if ($bid->getAgent() == $agent)
      {
        $bid->deactivate();
      }
    }
  }

  public function getAgentHasActiveBid(AuctionAgentInterface $agent)
  {
    foreach ($this->bids as $bid) 
    {
      if ($bid->getAgent() == $agent && $bid->isActive())
      {
        return true;
      }
    }

    return false;
  }

  public function getActiveBidForAgent(AuctionAgentInterface $agent)
  {
    foreach ($this->bids as $bid) 
    {
      if ($bid->getAgent() == $agent && $bid->isActive())
      {
        return $bid;
      }
    }

    return null;
  }

  public function getBidPositionForAgent(AuctionAgentInterface $agent)
  {
    foreach ($this->winning_bids as $pos => $bid) 
    {
      if ($bid->getAgent() == $agent)
      {
        return $pos;
      }
    }

    return null;
  }

  protected function getAgentHash(AuctionAgentInterface $agent)
  {
    return spl_object_hash($agent);
  }

  public function placeBid(Bid $bid)
  {
    $this->altered_at = microtime(true);

    $this->deactivateBidsByAgent($bid->getAgent());

    $bid->setAddedAt(microtime(true));
    $this->bids[] = $bid;
    $bid->setAuction($this);
    $this->reCalculateWinningBids();
  }

  public function pullOut(AuctionAgentInterface $agent)
  {
    $this->altered_at = microtime(true);

    $this->deactivateBidsByAgent($agent);

    $this->reCalculateWinningBids();
  }

  public function getAlteredAt()
  {
    return $this->altered_at;
  }
  
  public function setStopTime($stop_time)
  {
    $this->stop_time = $stop_time;
  }
  
  public function getStopTime()
  {
    return $this->stop_time;
  }
  
  public function setWinnerLimit($winner_limit)
  {
    $this->winner_limite = $winner_limit;
  }
  
  public function getWinnerLimit()
  {
    return $this->winner_limit;
  }
  
  public function setMinimunIncrement($minimum_increment)
  {
    $this->minimum_increment = $minimum_increment;
  }
  
  public function __toString()
  {
    return $this->getStopTime()->format('Y-m-d H:m:s');
  }

  public function getId()
  {
    return $this->id;
  }
}