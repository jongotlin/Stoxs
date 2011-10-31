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
        $content = $this->renderView('StoxsAppBundle:Default:loggedin.html.twig');
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
}