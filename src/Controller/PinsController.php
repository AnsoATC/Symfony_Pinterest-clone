<?php

namespace App\Controller;

use App\Entity\Pin;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PinRepository;
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
     * @Route("/" , name="app_home", methods={"GET"}) 
     */
    public function index(PinRepository $repo):Response
    { 

        $pins = $repo->findAll();

        return $this->render('pins/index.html.twig', ['pins'=>$pins]);
    }

    /**
     * @Route("/pins/{id<[0-9]+>}", name="app_pins_show")
     */
    public function show(Pin $pin):Response
    {
        return $this->render('pins/show.html.twig', compact('pin'));  
    }

    // /**
    //  * @Route("/pins/create", name="app_pins_create" , methods={"GET", "POST"})
    //  */
    // public function create(Request $request,EntityManagerInterface $em)
    // { 
    //     if($request->isMethod('POST')){
    //         $data = $request->request->all();
            
    //         if($this->isCsrfTokenValid('pins_create', $data["_token"])){

    //             $pin = new Pin();

    //             $pin->setTitle($data["title"]);
    //             $pin->setDescription($data["description"]);

    //             $em -> persist($pin);
    //             $em -> flush($pin);

    //         }

    //         return $this->redirectToRoute('app_home');

    //     }

    //     return $this->render('pins/create.html.twig');
    // }

    /**
     * @Route("/pins/create", name="app_pins_create" , methods={"GET", "POST"})
     */
    public function create(Request $request,EntityManagerInterface $em)
    { 
        $pin = new Pin();

        $form = $this->createFormBuilder($pin)
            ->add('title', TextType::class, ['attr' => ['autofocus' => true]])
            ->add('description', TextareaType::class, ['attr' => ['rows' => 10, 'cols' => 50]])
            // ->add('submit', SubmitType::class, ['label' => 'Create Pin'])
            ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em -> persist($pin);
            $em -> flush($pin);

            return $this->redirectToRoute('app_pins_show', ['id' => $pin->getId()]);
        }

        return $this->render('pins/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
