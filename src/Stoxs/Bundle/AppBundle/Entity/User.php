<?php

namespace Stoxs\Bundle\AppBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * @var string $ssn
     *
     * @ORM\Column(name="ssn", type="string", length=11, nullable=true)
     */
    private $ssn;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set ssn
     *
     * @param string $ssn
     */
    public function setSsn($ssn)
    {
        $this->ssn = $ssn;
    }

    /**
     * Get ssn
     *
     * @return string 
     */
    public function getSsn()
    {
        return $this->ssn;
    }
}