<?php

namespace Stoxs\Bundle\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class AgentType extends AbstractType
{
  
  public function getName()
  {
    return 'agent';
  }
  
  public function buildForm(FormBuilder $builder, array $options)
  {
    $builder->add('max_price')->add('min_position')->add('discr', 'choice', array('choices' => array('placemax' => 'Maximera plats', 'pricemin' => 'Minimera pris')));
  }
}