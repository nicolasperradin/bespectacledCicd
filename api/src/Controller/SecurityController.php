<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use App\Entity\BlacklistedToken;
use App\Security\UserConfirmationService;
use App\Security\UserTokenPassword;
use Symfony\Component\HttpFoundation\JsonResponse;


class SecurityController extends AbstractController
{


    public function __construct(private JWTTokenManagerInterface $jwtManager)
    {
    }

    #[Route('/api/login', name: 'app_login', methods: ['POST'])]
    public function login()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $token = $this->jwtManager->create($user);

        return $this->json([
            'user' => $this->getUser() ? $this->getUser() : null,
            'token' => $token
        ]);
    }


    #[Route('/confirm-user/{token}', name: 'default_confirm_token')]
    public function confirmUser(
        string $token,
        UserConfirmationService $userConfirmationService
    ) {
        $userConfirmationService->confirmUser($token);

        return $this->redirectToRoute('default_index');
    }

    #[Route('/forgot-password/{token}', name: 'forgot_password')]
    public function recoverPassword(
        string $token,
        UserTokenPassword $UserTokenPassword
    ) {
        $UserTokenPassword->setTokenAsPassword($token);
        return $this->redirect('https://localhost/login');
    }
}