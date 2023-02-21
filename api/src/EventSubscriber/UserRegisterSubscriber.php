<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Email\Mailer;
use App\Security\TokenGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
// use ApiPlatform\Core\EventListener\EventPriorities;
use ApiPlatform\Symfony\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;

class UserRegisterSubscriber implements EventSubscriberInterface
{


    public function __construct(
       private UserPasswordHasherInterface $userPasswordHasher,
       private TokenGenerator $tokenGenerator,
       private Mailer $mailer
    )
    {
    }

    /**
     * @return array|string[]
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['userRegistered', EventPriorities::PRE_WRITE],
        ];
    }

   // public function userRegistered(GetResponseForControllerResultEvent $event)
    public function userRegistered(ViewEvent $event)
    {

        $user = $event->getControllerResult();
        $method = $event->getRequest()
            ->getMethod();

        if (!$user instanceof User ||
            !in_array($method, [Request::METHOD_POST])) {
            return;
        }

        // It is an User, we need to hash password here
        /*$user->setPassword(
            $this->userPasswordHasher->hashPassword($user, $user->getPassword())
        );

        // Create confirmation token
        $user->setConfirmationToken(
            $this->tokenGenerator->getRandomSecureToken()
        );

        // Send e-mail here...
        $this->mailer->sendConfirmationEmail($user);*/
    }
}