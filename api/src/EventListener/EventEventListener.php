<?php

namespace App\EventListener;

use App\Entity\Event;
use App\Entity\Ticket;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use App\Repository\EventRepository;
use App\Repository\TicketRepository;

class EventEventListener
{

    public function __construct(private EventRepository $eventRepository, private TicketRepository $ticketRepository)
    {
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof Event) {
            return;
        }

        $event = $this->eventRepository->find($entity->getId());

        //find a ticket with this event
        $ticket = $this->ticketRepository->findOneBy(['event' => $event]);

        if ($ticket) {
            return;
        }
 
        $ticket = new Ticket();
        $ticket->setEvent($event);
        $ticket->setPrice($event->getPrice());
        $ticket->setStripeTicketId($event->getStripeTicketId());
        $ticket->setReference(uniqid());

   
        $entityManager = $args->getObjectManager();
        $entityManager->flush();
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof Event) {
            return;
        }

        $entityManager = $args->getObjectManager();
        $entityManager->flush();
    }

    public function postRemove(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof Event) {
            return;
        }

    }
}
