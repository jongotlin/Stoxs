<?php

namespace Stoxs\Bundle\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\ORM\Mapping as ORM;

use Stoxs\Bundle\AppBundle\Entity\Auction\Auction;
use Stoxs\Bundle\AppBundle\Entity\Auction\Agent;
use Stoxs\Bundle\AppBundle\Form\AgentFormModel;
use Stoxs\Bundle\AppBundle\Form\AgentType;

class AgentController extends Controller
{

  /**
   * @Route("/agent/new", name="agent_new_select")
   * @Template()
   */
  public function newSelectAction()
  {
      
      $em = $this->getDoctrine()->getEntityManager();
      
      $auctions = $em->getRepository('StoxsAppBundle:Auction\Auction')->findAllActiveForBiddingAuctions();
      if (count($auctions) == 1) {        
        return $this->redirect($this->generateUrl('agent_new', array('id' => $auctions[0]->getId())));
      }
      
      return array(
        'auctions' => $auctions
      );
  }
  
  /**
   * @Route("/agent/new/{id}", name="agent_new", requirements={ "id"="\d+" })
   * @Template()
   * @ParamConverter("auction", class="StoxsAppBundle:Auction\Auction")
   */
  public function newAction(Auction $auction)
  {
    
    $em = $this->getDoctrine()->getEntityManager();

    $agent_form_model = new AgentFormModel;
    $agent_form_model->auction = $auction;
    $agent_form_model->user = $this->container->get('security.context')->getToken()->getUser();
    $form = $this->createForm(new AgentType, $agent_form_model);
    
    $request = $this->getRequest();
    if ('POST' === $request->getMethod()) {
      $form->bindRequest($request);
      if ($form->isValid()) {
        $em->persist($agent_form_model->createAgent());
        $em->flush();
        
        return $this->redirect($this->generateUrl('new_agent_saved'));
      }
    }
    
    return array(
      'auction' => $auction,
      'form' => $form->createView()
    );
    
  }

  /**
   * @Route("/agent/new-saved", name="new_agent_saved")
   * @Template()
   */
  public function newSavedAction()
  {
      return array();
  }

  /**
   * @Route("/agent/edit", name="agent_edit")
   * @Template()
   */
  public function editAction()
  {
      return array();
  }

}
