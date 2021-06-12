<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 * @package App\Controller
 * @Route("/", name="home_")
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @return Response
     */
    public function index(): Response
    {
        $response = $this->json([
            'success' => true,
            'status' => 'Welcome to Nicolas Fez API\'s home page !'
        ]);

        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }
}
