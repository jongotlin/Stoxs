<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Stoxs\Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;

class ProfileFormType extends BaseType
{
    private $class;

    /**
     * @param string $class The User class name
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    public function buildForm(FormBuilder $builder, array $options)
    {
        $child = $builder->create('user', 'form', array('data_class' => $this->class));
        $this->buildUserForm($child, $options);

        $builder
            ->add($child)
            ->add('current', 'password')
            //->add('user.ssn')
        ;
    }

    public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'FOS\UserBundle\Form\Model\CheckPassword');
    }

    public function getName()
    {
        return 'stoxs_user_profile';
    }

    /**
     * Builds the embedded form representing the user.
     *
     * @param FormBuilder $builder
     * @param array $options
     */
    protected function buildUserForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('email', 'email')
            ->add('ssn')
            ->add('phoneNumber')
        ;
    }
}
