<?php

namespace App\Service\User;

use App\Entity\UGroup;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserManager
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * UserManager constructor.
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }


    /**
     * @return User[]
     */
    public function getAllUsers(): array
    {
        return $this->userRepository->findAll();
    }

    /**
     * @param User $user
     * @param UGroup $group
     * @return User
     */
    public function attachGroupToUser(User $user, UGroup $group): User
    {
        $user->addGroup($group);

        $this->entityManager->persist($user);

        return $user;
    }

    /**
     * @param User $user
     * @param UGroup $group
     * @return User
     */
    public function removeGroupFromUser(User $user, UGroup $group): User
    {
        $user->removeGroup($group);

        $this->entityManager->persist($user);

        return $user;
    }

    /**
     * @param User $user
     */
    public function delete(User $user): void
    {
        $this->entityManager->remove($user);
    }

    /**
     * @param $groupId
     * @return mixed
     */
    public function getUsersByGroup($groupId)
    {
        return $this->userRepository->findByGroup($groupId);
    }

}
