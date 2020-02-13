<?php

namespace App\Controller\Group;

use App\Entity\UGroup;
use App\Service\Group\GroupManager;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;

class GroupController extends AbstractFOSRestController
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var GroupManager
     */
    private $groupManager;

    /**
     * GroupController constructor.
     * @param EntityManagerInterface $entityManager
     * @param GroupManager $groupManager
     */
    public function __construct(EntityManagerInterface $entityManager, GroupManager $groupManager)
    {
        $this->entityManager = $entityManager;
        $this->groupManager = $groupManager;
    }

    /**
     * @SWG\Get(
     *      summary="Get groups",
     *      description="Get all groups",
     *      produces={"application/json"},
     *      tags={"UGroup"},
     *      @SWG\Response(
     *          response="200",
     *          description="success",
     *          @SWG\Schema(
     *              @SWG\Property(property="items", type="array", @SWG\Items(
     *                  ref=@Model(type=App\Entity\UGroup::class)
     *              ))
     *          )
     *      ),
     *      security={{"Bearer":{}}}
     * )
     *
     * @return View
     */
    public function getAllGroups(): View
    {
        $groups = $this->groupManager->getAllGroups();

        return $this->view(['items' => $groups]);
    }

    /**
     * @SWG\Post(
     *     summary="Create new UGroup entity",
     *     description="Creates and returns UGroup",
     *     produces={"application/json"},
     *     tags={"UGroup"},
     *     @SWG\Parameter(
     *       name="body",
     *       in="body",
     *       description="UGroup object",
     *       required=true,
     *       @Model(type=UGroup::class, groups={"Create"})
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="successful operation",
     *         @Model(type=UGroup::class, groups={"Get"})
     *     ),
     *     security={{"Bearer":{}}}
     * )
     *
     * @ParamConverter("group", converter="json_converter_validator")
     *
     * @param UGroup $group
     *
     * @return View
     */
    public function createGroup(UGroup $group): View
    {
        $this->entityManager->persist($group);
        $this->entityManager->flush();

        return $this->view($group, Response::HTTP_CREATED);
    }

    /**
     * @SWG\Delete(
     *     summary="Remove UGroup",
     *     description="Removes UGroup",
     *     produces={"application/json"},
     *     tags={"UGroup"},
     *     @SWG\Parameter(
     *         description="UGroup ID to delete",
     *         in="path",
     *         name="id",
     *         required=true,
     *         type="string",
     *         format="string"
     *     ),
     *     @SWG\Response(
     *         response=204,
     *         description="Removed successfully",
     *     ),
     *     security={{"Bearer":{}}}
     * )
     *
     * @param UGroup $group
     * @throws \Exception
     */
    public function deleteGroup(UGroup $group): void
    {
        $this->throwExceptionIfGroupUsed($group);

        $this->groupManager->delete($group);
        $this->entityManager->flush();
    }

    /**
     * @param UGroup $group
     * @throws \Exception
     */
    private function throwExceptionIfGroupUsed(UGroup $group): void
    {
        if ($this->groupManager->hasUsers($group)) {
            throw new \Exception('Group is still used by Users');
        }
    }
}
