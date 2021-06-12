<?php

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\Controller
 * @Route("/users", name="users_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $usersRepository = $this->getDoctrine()->getRepository(User::class);
        $usersList = $usersRepository->findAll();

        $response = $this->json(
            [
                'success' => true,
                'status' => 'Users retrieved.',
                'users' => $usersList
            ]);
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }

    /**
     * @Route("/signup", name="signup", methods={"POST"})
     * @return JsonResponse
     */
    public function signup(): JsonResponse
    {
        return $this->json([
            'success' => true,
            'status' => 'User created.'
        ]);
    }

    /**
     * @Route("/login", name="login", methods={"POST"})
     * @return JsonResponse
     */
    public function login(): JsonResponse
    {
        $user = $this->getUser();

        if (null === $user) {
            return $this->json([
                'success' => false,
                'status' => 'Invalid credentials'
            ]);
        }
        return $this->json([
            'success' => true,
            'status' => 'Login successful.',
            'user' => $user
        ]);
    }

    /**
     * @Route("/logout", name="logout", methods={"GET"})
     */
    public function logout()
    {
    }
}
