<?php

namespace Stoxs\Bundle\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class SmsType extends AbstractType
{
  
  public function getName()
  {
    return 'sms';
  }
  
  public function buildForm(FormBuilder $builder, array $options)
  {
    $builder->add('body');
  }
}