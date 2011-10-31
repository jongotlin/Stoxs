<?php

namespace Stoxs\Bundle\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Stoxs\Bundle\AppBundle\Entity\Auction\Auction;
use Stoxs\Bundle\AppBundle\Form\AuctionType;

class AuctionController extends Controller
{
    /**
     * @Route("/auction/new", name="new_auction")
     * @Template()
     */
    public function newAction()
    {
      
      $em = $this->getDoctrine()->getEntityManager();
      
      $auction = new Auction(100, 25);
      
      $form = $this->createForm(new AuctionType, $auction);
      
      $request = $this->getRequest();
      if ('POST' === $request->getMethod()) {
        $form->bindRequest($request);
        if ($form->isValid()) {
          $em->persist($auction);
          $em->flush();
          
          return $this->redirect($this->generateUrl('new_auction_completed'));
        }
      }
      
      return array(
        'auction' => $auction,
        'form' => $form->createView()
      );
      
    }
    
    /**
    * @Route("/sms/new-saved", name="new_auction_completed")
    * @Template()
     */
    public function newSavedAction()
    {
      return array();
    }
    
}
