<?php

namespace App\Service\Group;

use App\Entity\UGroup;
use App\Repository\UGroupRepository;
use App\Service\User\UserManager;
use Doctrine\ORM\EntityManagerInterface;

class GroupManager
{
    /**
     * @var UGroupRepository
     */
    private $groupRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * GroupManager constructor.
     * @param UGroupRepository $groupRepository
     * @param EntityManagerInterface $entityManager
     * @param UserManager $userManager
     */
    public function __construct(UGroupRepository $groupRepository, EntityManagerInterface $entityManager, UserManager $userManager)
    {
        $this->groupRepository = $groupRepository;
        $this->entityManager = $entityManager;
        $this->userManager = $userManager;
    }


    /**
     * @return UGroup[]
     */
    public function getAllGroups(): array
    {
        return $this->groupRepository->findAll();
    }

    /**
     * @param UGroup $group
     */
    public function delete(UGroup $group): void
    {
        $this->entityManager->remove($group);
    }

    /**
     * @param UGroup $group
     * @return mixed
     */
    public function hasUsers(UGroup $group)
    {
        return !empty($this->userManager->getUsersByGroup($group->getId()));
    }
}
