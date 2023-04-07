<?php

namespace App\Controller;


use App\Form\BankType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BankController extends AbstractController
{

    private UserRepository $userRepository;
    private EntityManagerInterface $em;

    /**
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(UserRepository $userRepository, EntityManagerInterface $em)
    {
        $this->userRepository = $userRepository;
        $this->em = $em;
    }

    #[Route('/user/bank/create', name: 'app_bank')]
    public function index(Request $request): Response
    {
        $user = $this->userRepository->find($this->getUser());
        $amount = $user->getBank()->getAmount();
        $user->getBank()->setAmount('0');
        $form = $this->createForm(BankType::class, $user->getBank());
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
                $user->getBank()->setAmount($user->getBank()->getAmount() + $amount);
                $this->em->persist($user);
                $this->em->flush();

                return $this->redirectToRoute('app_user');
        }
        return $this->render('bank/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
