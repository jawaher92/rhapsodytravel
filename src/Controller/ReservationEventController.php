<?php


namespace App\Controller;



use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ReservationEventController extends  Controller
{

    /**
     * @Route ("/")
     * @Method ({"GET", "POST"})
     */
    public function index() {
        return $this->render('home/index.html.twig');
    }

}