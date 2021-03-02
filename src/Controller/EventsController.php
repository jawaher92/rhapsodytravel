<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EventsController extends  Controller
{

    /**
     * @Route ("/")
     * @Method ({"GET", "POST"})
     */
    public function index() {

        $events = ['event1', 'event2'];

        return $this->render('home/index.html.twig', array('events' => $events));
    }

}