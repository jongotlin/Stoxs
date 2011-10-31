<?php

namespace Stoxs\Bundle\AppBundle\Tests\Auction;

use Stoxs\Bundle\AppBundle\Auction;

/**
* 
*/
class AuctionIntegrationTest extends \PHPUnit_Framework_TestCase
{
  public function testIntegration()
  {
    $auction = new Auction\Auction(5, 50);
    $auctioneer = new Auction\Auctioneer($auction);

    $agent1 = new Auction\PlaceMaximizingAgent(1300, 3);
    $agent2 = new Auction\PlaceMaximizingAgent(2000, 1);
    $agent3 = new Auction\PlaceMaximizingAgent(700, 5);
    $agent4 = new Auction\PlaceMaximizingAgent(1500, 4);
    $agent5 = new Auction\PlaceMaximizingAgent(2200, 2);
    $agent6 = new Auction\PlaceMaximizingAgent(1300, 2);
    $agent7 = new Auction\PlaceMaximizingAgent(5000, 4);

    $auctioneer->addAgent($agent1);
    $this->debugBidList($auction);
    $auctioneer->addAgent($agent2);
    $this->debugBidList($auction);
    $auctioneer->addAgent($agent3);
    $this->debugBidList($auction);
    $auctioneer->addAgent($agent4);
    $this->debugBidList($auction);
    $auctioneer->addAgent($agent5);
    $this->debugBidList($auction);
    $auctioneer->addAgent($agent6);
    $this->debugBidList($auction);
    $auctioneer->addAgent($agent7);
    $this->debugBidList($auction);

    $winning_bids = $auction->getWinningBids();

    $this->assertEquals($agent7, $winning_bids[0]->getAgent());
    $this->assertEquals(2200, $winning_bids[0]->getAmount());

    $this->assertEquals($agent5, $winning_bids[1]->getAgent());
    $this->assertEquals(2150, $winning_bids[1]->getAmount());

    $this->assertEquals($agent4, $winning_bids[2]->getAgent());
    $this->assertEquals(1450, $winning_bids[2]->getAmount()); // ?

    $this->assertEquals($agent3, $winning_bids[3]->getAgent());
    $this->assertEquals(650, $winning_bids[3]->getAmount());

    $this->assertInstanceOf('Stoxs\\Bundle\\AppBundle\\Auction\\NullBid', $winning_bids[4]);
  }

  public function debugBidList($auction)
  {
/*    echo "----- Bid list -----\n";
    foreach ($auction->getWinningBids() as $pos => $bid) 
    {
      if ($bid instanceOf Auction\NullBid)
      {
        echo ($pos+1).". Empty\n";
      }
      else
      {
        echo ($pos+1).". Agent with max-price ".$bid->getAgent()->getMaxPrice()." min-pos ".$bid->getAgent()->getMinPosition()." for ".$bid->getAmount()."\n";
      }
    }*/
  }
}