<?php

namespace Stoxs\Bundle\AppBundle\Tests\Auction;

use Stoxs\Bundle\AppBundle\Entity\Auction;

/**
* 
*/
class AuctionIntegrationTest extends \PHPUnit_Framework_TestCase
{
  public function testPlaceMaximizingIntegration()
  {
    $auction = new Auction\Auction(5, 50);

    $agent1 = new Auction\PlaceMaximizingAgent(1300, 3);
    $agent2 = new Auction\PlaceMaximizingAgent(2000, 1);
    $agent3 = new Auction\PlaceMaximizingAgent(700, 5);
    $agent4 = new Auction\PlaceMaximizingAgent(1500, 4);
    $agent5 = new Auction\PlaceMaximizingAgent(2200, 2);
    $agent6 = new Auction\PlaceMaximizingAgent(1300, 2);
    $agent7 = new Auction\PlaceMaximizingAgent(5000, 4);

    $auction->addAgent($agent1);
//    $this->debugBidList($auction);
    $auction->addAgent($agent2);
//    $this->debugBidList($auction);
    $auction->addAgent($agent3);
//    $this->debugBidList($auction);
    $auction->addAgent($agent4);
//    $this->debugBidList($auction);
    $auction->addAgent($agent5);
//    $this->debugBidList($auction);
    $auction->addAgent($agent6);
//    $this->debugBidList($auction);
    $auction->addAgent($agent7);
//    $this->debugBidList($auction);

    $winning_bids = $auction->getWinningBids();

    $this->assertEquals($agent7, $winning_bids[0]->getAgent());
    $this->assertEquals(2200, $winning_bids[0]->getAmount());

    $this->assertEquals($agent5, $winning_bids[1]->getAgent());
    $this->assertEquals(2150, $winning_bids[1]->getAmount());

    $this->assertEquals($agent4, $winning_bids[2]->getAgent());
    $this->assertEquals(1450, $winning_bids[2]->getAmount());

    $this->assertEquals($agent3, $winning_bids[3]->getAgent());
    $this->assertEquals(650, $winning_bids[3]->getAmount());

    $this->assertInstanceOf('Stoxs\\Bundle\\AppBundle\\Entity\\Auction\\NullBid', $winning_bids[4]);
  }

  public function testPriceMinimizingIntegration()
  {
    $auction = new Auction\Auction(5, 50);

    $agent1 = new Auction\PriceMinimizingAgent(1300, 3);
    $agent2 = new Auction\PriceMinimizingAgent(2000, 1);
    $agent3 = new Auction\PriceMinimizingAgent(700, 5);
    $agent4 = new Auction\PriceMinimizingAgent(1500, 4);
    $agent5 = new Auction\PriceMinimizingAgent(2200, 2);
    $agent6 = new Auction\PriceMinimizingAgent(1300, 2);
    $agent7 = new Auction\PriceMinimizingAgent(5000, 4);

    $auction->addAgent($agent1);
//    $this->debugBidList($auction);
    $auction->addAgent($agent2);
//    $this->debugBidList($auction);
    $auction->addAgent($agent3);
//    $this->debugBidList($auction);
    $auction->addAgent($agent4);
//    $this->debugBidList($auction);
    $auction->addAgent($agent5);
//    $this->debugBidList($auction);
    $auction->addAgent($agent6);
//    $this->debugBidList($auction);
    $auction->addAgent($agent7);
//    $this->debugBidList($auction);

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

  }

/*  public function testMixedBigIntegrationTest($value='')
  {
    $auction = new Auction\Auction(4000, 50);
    $auctioneer = new Auction\Auctioneer($auction);

    $agents = array();

    for ($i=0; $i < 30; $i++) { 
        if (mt_rand(0,1))
        {
            $agents[] = new Auction\PlaceMaximizingAgent(mt_rand(1, 100)*50, mt_rand(1,4000));
        }
        else
        {
            $agents[] = new Auction\PriceMinimizingAgent(mt_rand(1, 100)*50, mt_rand(1,4000));
        }
    }

    require_once "/Users/magnus/Developer/xhprof/xhprof_lib/config.php";
    require_once "/Users/magnus/Developer/xhprof/xhprof_lib/utils/xhprof_lib.php";
    require_once "/Users/magnus/Developer/xhprof/xhprof_lib/utils/xhprof_runs.php";

    $_SERVER['REQUEST_URI'] = "http://test.test/test/dummy";

    foreach ($agents as $idx => $agent) 
    {
        xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
        $start = microtime(true);
        $auctioneer->addAgent($agent);
//        $this->debugBidList($auction);
        $stop = microtime(true);
        $xhprof_data = xhprof_disable();

        echo "Added agent with index $idx\n";
        echo "Took ".($stop-$start)." secs\n";

        $xhprof_runs = new \XHProfRuns_Default();
        $runId = $xhprof_runs->save_run($xhprof_data, "Symfony");
    }
  }*/

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