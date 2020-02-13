<?php

namespace App\Entity;

use FOS\UserBundle\Model\User as BaseUser;

class Admin extends BaseUser
{
    protected $id;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }
}