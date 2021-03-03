<?php


namespace App\Controller;


use App\Entity\Events;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @ORM\Entity
 * @ORM\Table(name="events_controller")
 */
class EventsController extends  Controller
{




    /**
     * @Route ("/events/list")
     * @Method ({"GET", "POST"})
     */
    public function eventsList() {

        $events= $this->getDoctrine()->getRepository(Events::class)->findAll();

        return $this->render('Events/events.html.twig', array('events' => $events));
    }




    /**
     * @Route("/events/new", name="create_event")
     * Method({"GET", "POST"})
     */
    public function new(Request $request) {
        $event = new Events();

        $form = $this->createFormBuilder($event)
            ->add('titre', TextType::class, array(
                'required' => false,
                'attr' => array('class' => 'form-control')
            ))
            ->add('description', TextareaType::class)
            ->add('dateDebut', DateTimeType::class)
            ->add('dateFin', DateTimeType::class)

            ->add('save', SubmitType::class, array(
                'label' => 'Ajouter',
                'attr' => array('class' => 'btn btn-primary mt-3')
            ))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $event = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('event_list');
        }

        return $this->render('Events/new.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/event/edit/{id}", name="edit_event")
     * Method({"GET", "POST"})
     */
    public function edit(Request $request, $id) {
        $event = new Events();
        $event = $this->getDoctrine()->getRepository(Events::class)->find($id);

        $form = $this->createFormBuilder($event)
            ->add('titre', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('description', TextareaType::class, array(
                'required' => false,
                'attr' => array('class' => 'form-control')
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Update',
                'attr' => array('class' => 'btn btn-primary mt-3')
            ))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('event_list');
        }

        return $this->render('events/edit.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/event/{id}", name="event_show")
     */
    public function show($id) {
        $event = $this->getDoctrine()->getRepository(event::class)->find($id);

        return $this->render('events/show.html.twig', array('event' => $event));
    }

    /**
     * @Route("/event/delete/{id}")
     * @Method({"DELETE"})
     */
    public function delete(Request $request, $id) {
        $event = $this->getDoctrine()->getRepository(event::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($event);
        $entityManager->flush();

        $events= $this->getDoctrine()->getRepository(Events::class)->findAll();

        return $this->render('Events/events.html.twig', array('events' => $events));

    }





}