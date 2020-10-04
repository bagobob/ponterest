<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Repository\PinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Form\PinFormType;

class PinsController extends AbstractController
{

    /**
     * @Route("/", name="app_home", methods="GET")
     */
    public function index(PinRepository $pinRepository) :Response
    {

        $pins = $pinRepository->findBy([],['createdAt' => 'DESC']);

        return $this->render('pins/index.html.twig', [
            'controller_name' => 'PinsController',
            'pins'            => $pins,
        ]);
    }

    /**
     * @Route("/pins/{id<[0-9]+>}", name="app_pins_show", methods="GET")
     */
    public function show(Pin $pin) :Response
    {
        return $this->render('pins/show.html.twig', compact('pin')) ;
    }

    
    /**
     * @Route("/pins/{id<[0-9]+>}/edit", name="app_pins_edit", methods={"GET", "PUT"})
     */
    public function edit(Pin $pin, Request $request,EntityManagerInterface $em) :Response
    {

        $form = $this->createForm(PinFormType::class, $pin, [
            'method' => 'PUT'
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        { 
            $em->flush();

            return $this->redirectToRoute('app_home') ;
        }

        return $this->render('pins/edit.html.twig', [
            'pin'  => $pin,
            'form' => $form->createView()
        ]) ;
    }

     /**
     * @Route("/pins/create", name="app_pins_create", methods={"GET","POST"})
     */
    public function create(Request $request, EntityManagerInterface $em) :Response
    {
        $form = $this->createForm(PinFormType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $pin = $form->getData();
            $em->persist($pin);
            $em->flush();

            return $this->redirectToRoute('app_home') ;
        }


        return $this->render('pins/create.html.twig', [
            'form' => $form->createView()
        ]) ;
    }

     
    /**
     * @Route("/pins/{id<[0-9]+>}/delete", name="app_pins_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Pin $pin, EntityManagerInterface $em ) :Response
    {  
        if($this->isCsrfTokenValid('pins_deletion'.$pin->getId(), $request->request->get('csrf_token')))
        {
            $em->remove($pin);
            $em->flush();
        }
        return $this->redirectToRoute('app_home') ;
    }
}
