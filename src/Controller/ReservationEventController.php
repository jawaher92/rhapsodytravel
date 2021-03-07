<?php


namespace App\Controller;


use App\Entity\Event\ReservationsEvents;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;


/**
 * @ORM\Entity
 * @ORM\Table(name="reservationevents_controller")
 */
class ReservationEventController extends  Controller
{


    /**
     * @Route ("/reservationss/list/{id}")
     * @Method ({"GET", "POST"})
     */
    public function reservationsList($id) {

        $reservations= $this->getDoctrine()->getRepository(ReservationsEvents::class)->findBy($id);

        return $this->render('Reservations/reservations.html.twig', array('reservations' => $reservations));
    }




    /**
     * @Route("/reservations/new/{id}/{eventid}", name="create_reservation")
     * Method({"GET", "POST"})
     */
    public function new(Request $request,$id, $eventid) {
        $reservation = new ReservationsEvents();

        $form = $this->createFormBuilder($reservation)
            ->add('titre', TextType::class, array(
                'required' => true,
                'attr' => array('class' => 'form-control')
            ))
            ->add('description', TextareaType::class, array(
                'required' => true,
                'attr' => array('class' => 'form-control')
            ))
            ->add('dateDebut', DateTimeType::class, array(
                'required' => true,
                'attr' => array('class' => 'form-control')
            ))
            ->add('dateFin', DateTimeType::class, array(
                'required' => true,
                'attr' => array('class' => 'form-control')

            ))            ->add('prix_unite', NumberType::class, array(
                'label' => 'prix unitaire',  'required' => true,
                'attr' => array('class' => 'form-control')

            ))
            ->add('brochure', FileType::class, [
                'label' => 'Brochure',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ],
            ])
            ->add('save', SubmitType::class, array(
                'label' => 'Ajouter',
                'attr' => array('class' => 'btn btn-primary mt-3')
            ))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $event = $form->getData();
            $brochureFile = $form->get('brochure')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $event->setBrochureFilename($newFilename);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('events_list');
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

        $event = $this->getDoctrine()->getRepository(Events::class)->find($id);
        $event->setBrochureFilename(
            $this->getParameter('brochures_directory').'/'.$event->getBrochureFilename()
        );
        $form = $this->createFormBuilder($event)
            ->add('titre', TextType::class, array(
                'required' => true,
                'attr' => array('class' => 'form-control')
            ))
            ->add('description', TextareaType::class, array(
                'required' => true,
                'attr' => array('class' => 'form-control')
            ))
            ->add('dateDebut', DateTimeType::class, array(
                'required' => true,
                'attr' => array('class' => 'form-control')
            ))
            ->add('dateFin', DateTimeType::class, array(
                'required' => true,
                'attr' => array('class' => 'form-control')

            ))            ->add('prix_unite', NumberType::class, array(
                'label' => 'prix unitaire',  'required' => true,
                'attr' => array('class' => 'form-control')

            ))
            ->add('brochure', FileType::class, [
                'label' => 'Brochure',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ],
            ])
            ->add('save', SubmitType::class, array(
                'label' => 'Valider',
                'attr' => array('class' => 'btn btn-primary mt-3')
            ))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $brochureFile = $form->get('brochure')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $event->setBrochureFilename($newFilename);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('events_list');
        }

        return $this->render('Events/edit.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/event/{id}/details", name="event_details")
     */
    public function show($id) {
        $event = $this->getDoctrine()->getRepository(Events::class)->find($id);

        return $this->render('Events/show.html.twig', array('event' => $event));
    }

    /**
     * @Route("/event/delete/{id}")
     * @Method({"DELETE"})
     */
    public function delete(Request $request, $id) {
        $event = $this->getDoctrine()->getRepository(Events::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($event);
        $entityManager->flush();

        $events= $this->getDoctrine()->getRepository(Events::class)->findAll();
        return $this->render('Events/events.html.twig', array('events' => $events));

    }



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Events::class,
        ]);
    }



}