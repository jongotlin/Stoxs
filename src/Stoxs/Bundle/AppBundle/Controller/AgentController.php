<?php

namespace Stoxs\Bundle\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AgentController extends Controller
{

  /**
   * @Route("/agent/new", name="agent_new")
   * @Template()
   */
  public function newAction()
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
