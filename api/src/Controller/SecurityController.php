<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use App\Entity\BlacklistedToken;
use App\Security\UserConfirmationService;
use App\Security\UserRecoverService;
use App\Security\UserTokenPassword;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;

class SecurityController extends AbstractController
{


    public function __construct(private JWTTokenManagerInterface $jwtManager)
    {
    }

    #[Route('/api/login', name: 'app_login', methods: ['POST'])]
    public function login(TokenStorageInterface $tokenStorage): JsonResponse
    {
        $user = $tokenStorage->getToken()->getUser();
        $token = $this->jwtManager->create($user);

        return $this->json([
            'user' => $this->getUser() ? $this->getUser() : null,
            'token' => $token
        ]);
    }

    #[Route('/api/forgot-password/', name: 'forgot_password', methods: ['POST'])]
    public function recoverPassword(
        UserRecoverService $userRecoverService,
        RequestStack $requestStack
    ) {
        $request = $requestStack->getCurrentRequest();
        $content = $request->getContent();
        $data = json_decode($content, true);
        $email = $data['email'];

        $userRecoverService->recoverPassword($email);
        return $this->json([
            'message" => "Email Sent if user exists'
        ]);
    }


    #[Route('/confirm-user/{token}', name: 'default_confirm_token')]
    public function confirmUser(
        string $token,
        UserConfirmationService $userConfirmationService
    ) {
        $userConfirmationService->confirmUser($token);

        return $this->redirect('http://localhost:8080');
    }

    #[Route('/forgot-password/{token}', name: 'forgot_password_token')]
    public function recoverPasswordToken(
        string $token,
        UserTokenPassword $UserTokenPassword
    ) {
        $UserTokenPassword->setTokenAsPassword($token);
        return $this->redirect('http://localhost:8080/login');
    }
}
