<?php

namespace Stoxs\Bundle\AppBundle\Entity\Auction;

use Doctrine\ORM\EntityRepository;

/**
 * AuctionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AgentRepository extends EntityRepository
{
  public function findForEdit()
  {
    return $this->createQueryBuilder('a')->
//      where('a.stop_time >= :stop_time')->
//      setParameter('stop_time', new \DateTime())->
      getQuery()->execute();
  }
}