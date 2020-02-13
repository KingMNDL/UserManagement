<?php

namespace App\Entity;

use Swagger\Annotations as SWG;

class UGroup
{
    /**
     * @SWG\Property(description="The unique identifier of the user.")
     *
     * @var int
     */
    private $id;

    /**
     * @SWG\Property(description="UGroup name", example="Members")
     *
     * @var string
     */
    private $name;

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
}
