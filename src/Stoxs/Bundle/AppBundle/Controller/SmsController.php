<?php

namespace Stoxs\Bundle\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Stoxs\Bundle\AppBundle\Entity\Sms;
use Stoxs\Bundle\AppBundle\Form\SmsType;

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
      
      $form = $this->createForm(new SmsType, $sms);
      
      $request = $this->getRequest();
      if ('POST' === $request->getMethod()) {
        $form->bindRequest($request);
        if ($form->isValid()) {
          $em->persist($sms);
          $em->flush();
          
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
