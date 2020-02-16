<?php

namespace App\Controller\User;

use App\Entity\UGroup;
use App\Entity\User;
use App\Service\User\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractFOSRestController
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * UserController constructor.
     * @param EntityManagerInterface $entityManager
     * @param UserManager $userManager
     */
    public function __construct(EntityManagerInterface $entityManager, UserManager $userManager)
    {
        $this->entityManager = $entityManager;
        $this->userManager = $userManager;
    }


    /**
     * @SWG\Get(
     *      summary="Get users",
     *      description="Get all users",
     *      produces={"application/json"},
     *      tags={"User"},
     *
     *      @SWG\Response(
     *          response="200",
     *          description="success",
     *          @SWG\Schema(
     *              @SWG\Property(property="items", type="array", @SWG\Items(
     *                  ref=@Model(type=App\Entity\User::class)
     *              ))
     *          )
     *      ),
     *      security={{"Bearer":{}}}
     * )
     *
     *
     * @return View
     */
    public function getAllUsers(): View
    {
        $users = $this->userManager->getAllUsers();

        return $this->view(['items' => $users]);
    }


    /**
     * @SWG\Post(
     *     summary="Create new User entity",
     *     description="Creates and returns User",
     *     produces={"application/json"},
     *     tags={"User"},
     *     @SWG\Parameter(
     *       name="body",
     *       in="body",
     *       description="User object",
     *       required=true,
     *       @Model(type=User::class, groups={"Create"})
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="successful operation",
     *         @Model(type=User::class, groups={"Get"})
     *     ),
     *      @SWG\Response(
     *          response="422",
     *          description="Invalid request",
     *          @SWG\Schema(ref="#/definitions/ValidationError")
     *      ),
     *     security={{"Bearer":{}}}
     * )
     *
     * @ParamConverter("user", converter="json_converter_validator")
     *
     * @param User $user
     *
     * @return View
     */
    public function createUser(User $user): View
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->view($user, Response::HTTP_CREATED);
    }

    /**
     * @SWG\Put(
     *     tags={"User"},
     *     summary="Assign group to user",
     *     description="Update user",
     *       @SWG\Parameter(
     *          description="User ID",
     *          in="path",
     *          allowEmptyValue=false,
     *          required=true,
     *          name="user",
     *          type="string",
     *          format="string"
     *      ),
     *      @SWG\Parameter(
     *          description="Group ID",
     *          in="path",
     *          allowEmptyValue=false,
     *          required=true,
     *          name="group",
     *          type="string",
     *          format="string"
     *      ),
     *      @SWG\Response(
     *          response="204",
     *          description="Updated",
     *      ),
     *      @SWG\Response(
     *          response=404,
     *          description="Not found",
     *          @SWG\Schema(ref="#/definitions/NotFoundError")
     *      ),
     *     security={{"Bearer":{}}}
     * )
     *
     * @param User $user
     * @param UGroup $group
     *
     * @return Response
     */
    public function addUserGroup(User $user, UGroup $group): Response
    {
        $user = $this->userManager->attachGroupToUser($user, $group);

        $this->entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @SWG\Put(
     *     tags={"User"},
     *     summary="Remove group from user",
     *     description="Update user",
     *       @SWG\Parameter(
     *          description="User ID",
     *          in="path",
     *          allowEmptyValue=false,
     *          required=true,
     *          name="user",
     *          type="string",
     *          format="string"
     *      ),
     *      @SWG\Parameter(
     *          description="Group ID",
     *          in="path",
     *          allowEmptyValue=false,
     *          required=true,
     *          name="group",
     *          type="string",
     *          format="string"
     *      ),
     *      @SWG\Response(
     *          response="204",
     *          description="Updated",
     *      ),
     *      @SWG\Response(
     *          response=404,
     *          description="Not found",
     *          @SWG\Schema(ref="#/definitions/NotFoundError")
     *      ),
     *     security={{"Bearer":{}}}
     * )
     *
     * @param User $user
     * @param UGroup $group
     *
     * @return Response
     */
    public function removeUserGroup(User $user, UGroup $group): Response
    {
        $user = $this->userManager->removeGroupFromUser($user, $group);

        $this->entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @SWG\Delete(
     *     summary="Remove User",
     *     description="Removes User",
     *     produces={"application/json"},
     *     tags={"User"},
     *     @SWG\Parameter(
     *         description="User ID to delete",
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
     *      @SWG\Response(
     *          response=404,
     *          description="Not found",
     *          @SWG\Schema(ref="#/definitions/NotFoundError")
     *      ),
     *     security={{"Bearer":{}}}
     * )
     *
     * @param User $user
     *
     * @return Response
     */
    public function deleteUser(User $user): Response
    {
        $this->userManager->delete($user);
        $this->entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
