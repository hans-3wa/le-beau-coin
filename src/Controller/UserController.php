<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\Voter\UserVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserController extends AbstractController
{
    private UserRepository $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
{
    $this->userRepository = $userRepository;
}

    /**
     * @param User $user
     * @return Response
     */
    #[Route('/profile', name: 'app_user', requirements: ['id' => '\d+'])]
    public function index(): Response
    {
        // Si vous voulez utilisez les id et donc les voter
        //$this->denyAccessUnlessGranted('view', $user);
        $user = $this->userRepository->find($this->getUser());

        return $this->render('user/index.html.twig', [
            'user' => $user
        ]);
    }


}
