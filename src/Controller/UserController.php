<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;

class UserController extends AbstractFOSRestController
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /* Find ALL users */

    /**
     * @Rest\Get("/api/users")
     */
    public function getApiUsers(){
        $users = $this->userRepository->findAll();
        return $this->view($users);
    }

    /* Find an user by EMAIL */

    /**
     * @Rest\Get("/api/users/{email}")
     */
    public function getApiUser(User $user){
        return $this->view($user);
    }

    /* Post an user */

    /**
     * @Rest\Post("/api/users")
     * @ParamConverter("user", converter="fos_rest.request_body")
     */
    public function postApiUser(User $user, Request $request){

        $user = new User();
        $user->setLastname($request->get('lastname'));
        $user->setFirstname($request->get('firstname'));
        $user->setEmail($request->get('email'));
        $user->setRoles($request->get('roles'));
        $user->setApiKey($request->get('apiKey'));
        $user->setApiKey($request->get('apiKey'));
       // $user->setPassword($request->get('password'));

        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();

        return $this->view($user);
    }

    /* Find ALL user SERIALIZED [id] */

    /**
     * @Rest\Get("/api/userss")
     * @Rest\View(serializerGroups={"user"})
     */
    public function getUser()
    {
        $users = $this->userRepository->findAll();
        return $this->view($users);
    }

    /* PATCH an user [firstname] */

    /**
     * @Rest\Patch("/api/users/{email}")
     */
    public function patchApiUser(User $user, Request $request){
        $attributeName = ['firstname' => 'setFirstname'];
        foreach ($attributeName as $key => $setterName) {
            if ($request->get($key) === null) {
                continue;
            }
            $user->$setterName($request->get($key));
        }

        $this->getDoctrine()->getManager()->flush();
        return $this->view($user);
    }

    /* DELETE an user */

    /**
     * @Rest\Delete("/api/users/{email}")
     */
    public function deleteApiUser(User $user){
        $this->getDoctrine()->getManager()->remove($user);
        $this->getDoctrine()->getManager()->flush();
        return $this->view($user);
    }
}
