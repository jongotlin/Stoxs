<?php

namespace Stoxs\Bundle\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class AgentEditType extends AbstractType
{
  
  public function getName()
  {
    return 'agent_edit';
  }
  
  public function buildForm(FormBuilder $builder, array $options)
  {
    $builder->add('max_price')->add('min_position');
  }
}