<?php

namespace Stoxs\Bundle\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="start")
     */
    public function indexAction()
    {

      if (false !== $this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
        $current_auctions = $this->get('doctrine')->getRepository('StoxsAppBundle:Auction\Auction')->findAllActiveForBiddingAuctions();

        $content = $this->renderView('StoxsAppBundle:Default:loggedin.html.twig', array('current_auction' => $current_auctions[0]));
      } else {
        $content = $this->renderView('StoxsAppBundle:Default:index.html.twig');
      }
      return new Response($content);
    }
    
    /**
     * @Route("/how-it-works", name="how_it_works")
     * @Template()
     */
    public function howItWorksAction()
    {
      return array();
    }
    
    /**
     * @Route("/current-bids", name="current_bids")
     * @Template()
     */
    public function currentBidsAction()
    {
      
      $em = $this->getDoctrine()->getEntityManager();
      
      $auction = $em->getRepository('StoxsAppBundle:Auction\Auction')->findNextEndingAuction();
      
      return array(
        'auction' => $auction
      );
    }
}
