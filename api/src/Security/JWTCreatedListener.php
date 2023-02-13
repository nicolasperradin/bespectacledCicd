<?php

namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class JWTCreatedListener {

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(private RequestStack $requestStack)
    {
    }

    /**
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $request = $this->requestStack->getCurrentRequest();

        $payload       = $event->getData();
        $payload['username'] = $event->getUser()->getUsername();
        $payload['id'] = $event->getUser()->getId();

        $event->setData($payload);
    }
}