<?php

namespace Stoxs\Bundle\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Stoxs\Bundle\AppBundle\Entity\Sms;
use Stoxs\Bundle\AppBundle\Entity\SmsAuctions;
use Stoxs\Bundle\AppBundle\Entity\Auction\AuctionRepository;

use Stoxs\Bundle\AppBundle\Form\SmsAuctionsType;

class SmsController extends Controller
{
    /**
     * @Route("/sms/send", name="send_sms")
     * @Template()
     */
    public function sendAction()
    {
      
      $em = $this->getDoctrine()->getEntityManager();

      $user = $this->container->get('security.context')->getToken()->getUser();
      
      $sms = new Sms;
      $sms->setUser($user);
      $sms->addRecipientUser($user);
      
      $sms_auctions = new SmsAuctions;
      $sms_auctions->sms = $sms;

      
      //$auctions = $this->getRepository('StoxsAppBundle:Auction')->findAllEndedAuctions();
      
      $form = $this->createForm(new SmsAuctionsType, $sms_auctions);
      
      $request = $this->getRequest();
      if ('POST' === $request->getMethod()) {
        $form->bindRequest($request);
        if ($form->isValid()) {
          $em->persist($sms_auctions->sms);
          $em->flush();

          $this->get('stoxs.sms_queue')->enqueueSms($sms);
//          $this->get('jgi_messy.message_center')->send($sms);
          
          return $this->redirect($this->generateUrl('send_sms_completed'));
        }
      }
      
      return array(
        'sms' => $sms,
        'form' => $form->createView()
      );
      
    }
    
    /**
    * @Route("/sms/send-completed", name="send_sms_completed")
    * @Template()
     */
    public function sendCompletedAction()
    {
      return array();
    }
    
}
