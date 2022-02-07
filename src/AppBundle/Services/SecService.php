<?php

namespace App\Services;

use EasyCorp\Bundle\EasySecurityBundle\Security\Security;

class SecService
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function myMethod()
    {
        // ...
        $user = $this->security->getUser();
    }
}
