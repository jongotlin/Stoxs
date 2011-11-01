<?php

namespace Stoxs\Bundle\AppBundle\Tests\Auction;

use Stoxs\Bundle\AppBundle\Entity\Auction;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


/**
* 
*/
class PersistedAuctionTests extends WebTestCase
{

  public function testStuff()
  {
/*    $client = static::createClient();

    $auction = new Auction\Auction(5, 50);
    $auction->setStopTime(new \DateTime('+2 days'));

    $agent1 = new Auction\PriceMinimizingAgent(1300, 3);
    $agent2 = new Auction\PriceMinimizingAgent(2000, 1);
    $agent3 = new Auction\PriceMinimizingAgent(700, 5);
    $agent4 = new Auction\PriceMinimizingAgent(1500, 4);
    $agent5 = new Auction\PriceMinimizingAgent(2200, 2);
    $agent6 = new Auction\PriceMinimizingAgent(1300, 2);
    $agent7 = new Auction\PriceMinimizingAgent(5000, 4);

    $auction->addAgent($agent1);
    $auction->addAgent($agent2);
    $auction->addAgent($agent3);
    $auction->addAgent($agent4);
    $auction->addAgent($agent5);
    $auction->addAgent($agent6);
    $auction->addAgent($agent7);

    $winning_bids = $auction->getWinningBids();

    $this->assertEquals($agent2, $winning_bids[0]->getAgent());
    $this->assertEquals(1300, $winning_bids[0]->getAmount());

    $this->assertEquals($agent5, $winning_bids[1]->getAgent());
    $this->assertEquals(1300, $winning_bids[1]->getAmount());

    $this->assertEquals($agent7, $winning_bids[2]->getAgent());
    $this->assertEquals(1300, $winning_bids[2]->getAmount());

    $this->assertEquals($agent4, $winning_bids[3]->getAgent());
    $this->assertEquals(1250, $winning_bids[3]->getAmount());

    $this->assertEquals($agent3, $winning_bids[4]->getAgent());
    $this->assertEquals(50, $winning_bids[4]->getAmount());

    $container = $client->getContainer();

    $em = $container->get('doctrine')->getEntityManager();

    $em->persist($auction);
    $em->flush();
*/
  }
  

  public function testOtherStuff()
  {
/*    $client = static::createClient();

    $container = $client->getContainer();

    $em = $container->get('doctrine')->getEntityManager();

    $auction = $em->getRepository('StoxsAppBundle:Auction\Auction')->find(1);
    $this->debugBidList($auction);

    $agent8 = new Auction\PriceMinimizingAgent(2400, 4);
    $agent9 = new Auction\PriceMinimizingAgent(1200, 5);

    $auction->addAgent($agent8);
    $auction->addAgent($agent9);

    $this->debugBidList($auction);

    $em->flush();*/
  }

  public function testYetMoreOtherStuff()
  {
    $client = static::createClient();

    $container = $client->getContainer();

    $em = $container->get('doctrine')->getEntityManager();

    $auction = $em->getRepository('StoxsAppBundle:Auction\Auction')->find(2);
    $user = $em->getRepository('StoxsAppBundle:User')->find(3);
    $this->debugBidList($auction);

    $agent10 = new Auction\PriceMinimizingAgent(12000, 5);
    $agent10->setUser($user);

    $auction->addAgent($agent10);

    $this->debugBidList($auction);

    $em->flush();
  }


  public function debugBidList($auction)
  {
    echo "----- Bid list -----\n";
    foreach ($auction->getWinningBids() as $pos => $bid) 
    {
      if ($bid instanceOf Auction\NullBid)
      {
        echo ($pos+1).". Empty\n";
      }
      else
      {
        echo ($pos+1).". ".$bid->getAgent()." for ".$bid->getAmount()."\n";
      }
    }
  }
}