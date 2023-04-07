<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddressController extends AbstractController
{
    private EntityManagerInterface $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/user/address/create', name: 'app_user_address_create')]
    public function create(Request $request): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $address->setUser($this->getUser());
            $this->em->persist($address);
            $this->em->flush();

            return $this->redirectToRoute('app_user');
        }
        return $this->render('address/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/user/address/update/{id}', name: 'app_user_address_update')]
    public function update(Address $address, Request $request): Response
    {
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $address->setUser($this->getUser());
            $this->em->persist($address);
            $this->em->flush();

            return $this->redirectToRoute('app_user');
        }
        return $this->render('address/create.html.twig', [
            'form' => $form->createView()
        ]);
    }


    #[Route('/user/address/delete/{id}', name: 'app_user_address_delete', methods: ['DELETE'])]
    public function delete(Address $address, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete' . $address->getId(), $request->get('_token'))) {
            $this->em->remove($address);
            $this->em->flush();
        }

        return $this->redirectToRoute("app_user");
    }
}
