<?php

namespace Stoxs\Bundle\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class AuctionType extends AbstractType
{
  
  public function getName()
  {
    return 'auction';
  }
  
  public function buildForm(FormBuilder $builder, array $options)
  {
    $builder->add('winner_limit')->add('minimum_increment')
      ->add('stop_time', null, array('widget' => 'single_text'));
  }
}