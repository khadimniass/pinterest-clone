<?php

namespace App\Controller;

use App\Repository\PinRepository;
use App\Entity\Pin;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PinsController extends AbstractController
{
    /**
     * @Route("/",methods={"GET"})
     */
    public function index(PinRepository $repo): Response
    {
      	$pins=$repo->findAll();
        return $this->render('pins/index.html.twig',['pins'=>$repo->findAll()]);
    }
    /**
     * @Route("/pins/{id<[0-9]+>}")
     */
    public function show(Pin $pin): Response
    {
        return $this->render('pins/show.html.twig',compact('pin'));
    }
    /**
     * @Route("/pins/create",methods={"GET","POST"})
     */

    public function create(Request $request,EntityManagerInterface $em): Response
    {
      $pin=new Pin;

      $form=$this->createFormBuilder($pin)
        ->add('title',null, ['attr'=>['autofocus'=>true]])
        ->add('description',null)
        ->getForm()
      ;

      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($pin);
        $em->flush();
        return $this->redirectToRoute('app_pins_show',['id' =>$pin->getId()]);
      }

      return $this->render('pins/create.html.twig',[
        'monFormulaire'=>$form->createview()
      ]);
    }
}
