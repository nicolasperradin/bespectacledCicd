<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController 
{
    public function __invoke()
    {
        return ($this->getUser()) ? $this->getUser() : null;
    }
}