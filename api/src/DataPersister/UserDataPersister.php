<?php
// src/DataPersister/UserDataPersister.php

namespace App\DataPersister;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Email\Mailer;
use App\Security\TokenGenerator;

/**
 *
 */
class UserDataPersister implements ContextAwareDataPersisterInterface
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private ManagerRegistry $doctrine,
        private UserPasswordHasherInterface $userPasswordHasher,
        private TokenGenerator $tokenGenerator,
        private Mailer $mailer
    ) {}

    /**
     * {@inheritdoc}
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof User;
    }

    /**
     * @param User $data
     */
    public function persist($data, array $context = [])
    {
        if ($data->getPlainPassword()) {

            $data->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $data,
                    $data->getPlainPassword()
                )
            );

            $data->eraseCredentials();
            $data->setConfirmationToken(
                $this->tokenGenerator->getRandomSecureToken()
            );
    
            // Send e-mail here...
            $this->mailer->sendConfirmationEmail($data);
        }

        $amountOfUsers = $this->doctrine->getRepository(User::class)->countall();

        if($amountOfUsers == 0){
            $data->setRoles(array("ROLE_ADMIN"));
        }

        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function remove($data, array $context = [])
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}