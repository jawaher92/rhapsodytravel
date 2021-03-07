<?php


namespace App\Controller;



use App\Entity\Event\Events;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends  Controller
{

    /**
     * @Route ("/")
     * @Method ({"GET", "POST"})
     */
    public function index() {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $events= $this->getDoctrine()->getRepository(Events::class)->findBy(array(),array('id'=>'DESC'),3,0);

        return $this->render('home/index.html.twig', array('events' => $events));
    }

}