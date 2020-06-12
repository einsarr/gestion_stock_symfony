<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function index()
    {
        return $this->render('login/index.html.twig');
    }
    /**
     * @Route("/", name="index")
     */
     public function login()
     {
         return $this->render('login/index.html.twig');
     }
     /**
     * @Route("/logon", name="logon")
     */
    public function logon()
    {
        return $this->redirectToRoute('accueil');
    }
     
    
}
