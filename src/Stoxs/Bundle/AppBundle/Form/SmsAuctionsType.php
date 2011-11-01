<?php

namespace Stoxs\Bundle\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Stoxs\Bundle\AppBundle\Entity\SmsAuctions;
use Stoxs\Bundle\AppBundle\Entity\Auction\Auction;

class SmsAuctionsType extends AbstractType
{
  
  
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('body', 'textarea');
        
         $builder->add('auctions', 'entity', array(
              'class' => 'StoxsAppBundle:Auction\Auction',
              'query_builder' => function( $er) {
                  return $er->createQueryBuilder('a')->select()->
                    where('a.stop_time < :stop_time')->
                    setParameter('stop_time', new \DateTime());
              },
          ));
       
    }



  public function getName()
  {
    return 'smsAuctions';
  }

}