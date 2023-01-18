<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\UserConfirmation;
use App\Security\UserConfirmationService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use App\Security\UserRecoverService;

class UserEmailSubscriber implements EventSubscriberInterface
{


    public function __construct(private UserRecoverService $UserRecoverService) 
    {
    }

    /**
     * @return array|string[]
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => [
                'recoverPassword',
                EventPriorities::POST_VALIDATE,
            ],
        ];
    }

   // public function recoverPassword(GetResponseForControllerResultEvent $event)
    public function recoverPassword(ViewEvent $event)
    {
        $request = $event->getRequest();

        if ('api_user_emails_post_collection' !==
            $request->get('_route')) {
            return;
        }

        /** @var UserConfirmation $confirmationToken */
        $email = $event->getControllerResult()->email;

/*         var_dump($email); */
        $this->UserRecoverService->recoverPassword($email);

        $event->setResponse(new JsonResponse(null, Response::HTTP_OK));
    }
}