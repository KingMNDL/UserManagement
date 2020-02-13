<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PhpCollection\CollectionInterface;
use Swagger\Annotations as SWG;

class User
{
    /**
     * @SWG\Property(description="Group", example="1")
     *
     * @var ArrayCollection|CollectionInterface|UGroup[]|string[]
     */
    protected $groups;
    /**
     * @SWG\Property(description="The unique identifier of the user.")
     *
     * @var int
     */
    private $id;
    /**
     * @SWG\Property(description="User name", example="Mindaugas")
     *
     * @var string
     */
    private $name;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param UGroup $group
     *
     * @return $this
     */
    public function addGroup($group)
    {
        if (!$this->hasGroup($group)) {
            $this->groups->add($group);
        }

        return $this;
    }

    /**
     * @param $group
     * @return bool
     */
    public function hasGroup($group): bool
    {
        $rawRole = $group instanceof UGroup ? $group->getId() : $group;
        foreach ($this->getGroups() as $existingRole) {
            $existingRawRole = $existingRole instanceof UGroup ? $existingRole->getId() : $existingRole;

            if ($rawRole === $existingRawRole) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get roles.
     *
     * @return array
     */
    public function getGroups()
    {
        return $this->groups->toArray();
    }

    /**
     * Sets the roles of the user.
     *
     * This overwrites any previous roles.
     *
     * @param array $groups
     *
     * @return static
     */
    public function setGroups(array $groups): User
    {
        $this->groups = new ArrayCollection($groups);

        return $this;
    }

    /**
     * @param UGroup $group
     */
    public function removeGroup($group)
    {
        $this->groups->removeElement($group);
    }
}
