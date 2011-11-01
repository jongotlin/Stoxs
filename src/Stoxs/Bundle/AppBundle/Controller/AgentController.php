<?php

namespace Stoxs\Bundle\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\ORM\Mapping as ORM;

use Stoxs\Bundle\AppBundle\Entity\Auction\Auction;
use Stoxs\Bundle\AppBundle\Entity\Auction\BaseAgent;
use Stoxs\Bundle\AppBundle\Form\AgentFormModel;
use Stoxs\Bundle\AppBundle\Form\AgentEditFormModel;
use Stoxs\Bundle\AppBundle\Form\AgentType;
use Stoxs\Bundle\AppBundle\Form\AgentEditType;

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
        $agent_form_model->createAgent();
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
   * @Route("/agent/edit", name="agent_edit_select")
   * @Template()
   */
  public function editSelectAction()
  {
      $em = $this->getDoctrine()->getEntityManager();

      $agents = $em->getRepository('StoxsAppBundle:Auction\BaseAgent')->findForEdit();
      
      return array(
        'agents' => $agents
      );
  }

  /**
   * @Route("/agent/edit/{id}", name="agent_edit", requirements={ "id"="\d+" })
   * @Template()
   * @ParamConverter("auction", class="StoxsAppBundle:Auction\BaseAgent")
   */
  public function editAction(BaseAgent $agent)
  {
    $em = $this->getDoctrine()->getEntityManager();

    $agent_edit_form_model = new AgentEditFormModel;
    $agent_edit_form_model->agent = $agent;
    //echo $agent->getMaxPrice();
    $form = $this->createForm(new AgentEditType, $agent_edit_form_model);
    
    $request = $this->getRequest();
    if ('POST' === $request->getMethod()) {
      $form->bindRequest($request);
      if ($form->isValid()) {
        $em->persist($agent_edit_form_model->agent);
        $agent_edit_form_model->agent->getAuction()->processAgents();
        $em->flush();
        
        return $this->redirect($this->generateUrl('edit_agent_saved'));
      }
    }
    
    return array(
      'agent' => $agent,
      'form' => $form->createView()
    );
  }
  
  /**
   * @Route("/agent/edit-saved", name="edit_agent_saved")
   * @Template()
   */
  public function editSavedAction()
  {
    return array();
  }
}
