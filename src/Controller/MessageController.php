<?php

namespace App\Controller;

use App\Entity\Message;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MessageController
 * @package App\Controller
 * @Route("/messages", name="messages_")
 */
class MessageController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $messagesRepository = $this->getDoctrine()->getRepository(Message::class);
        $messagesList = $messagesRepository->findAll();
        $response = $this->json([
            'success' => true,
            'status' => 'Messages retrieved.',
            'messages' => $messagesList
        ]);

        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }

    /**
     * @Route("/", name="create", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $requestData = $request->toArray();
        $entityManager = $this->getDoctrine()->getManager();
        $message = new Message();

        if (!isset($requestData['sender_name']) or !isset($requestData['sender_email']) or
            !isset($requestData['object']) or !isset($requestData['content'])) {
            $response = $this->json([
                'success' => false,
                'status' => 'Message body is incomplete.'
            ]);
            $response->setStatusCode(403);
            $response->headers->set('Access-Control-Allow-Origin', '*');
            return $response;
        }
        $message->setSenderName($requestData['sender_name']);
        $message->setSenderEmail($requestData['sender_email']);
        $message->setObject($requestData['object']);
        $message->setContent($requestData['content']);
        $entityManager->persist($message);
        $entityManager->flush();
        $response = $this->json([
            'success' => true,
            'status' => 'Message created.',
            'message' => $message
        ]);
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }

    /**
     * @Route("/", name="destroy", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     * @return JsonResponse
     */
    public function destroy(): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $messagesList = $entityManager->getRepository(Message::class)->findAll();

        foreach ($messagesList as $message) {
            $entityManager->remove($message);
        }
        $entityManager->flush();
        $messagesList = $entityManager->getRepository(Message::class)->findAll();
        $response = $this->json([
            'success' => true,
            'status' => 'Messages destroyed.',
            'messages' => $messagesList
        ]);
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $message = $this->getDoctrine()->getRepository(Message::class)->find($id);

        if (null === $message) {
            $response = $this->json([
                'success' => false,
                'status' => 'Message ' . $id . ' doesn\'t exist.',
            ]);
            $response->setStatusCode(403);
            $response->headers->set('Access-Control-Allow-Origin', '*');
            return $response;
        }
        $response = $this->json([
            'success' => true,
            'status' => 'Message retrieved.',
            'message' => $message
        ]);
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }

    /**
     * @Route("/{id}", name="edit", methods={"PUT"})
     * @IsGranted("ROLE_ADMIN")
     * @return JsonResponse
     */
    public function edit(): JsonResponse
    {
        $response = $this->json([
            'success' => false,
            'status' => 'Message edition is not supported.'
        ]);

        $response->setStatusCode(403);
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     * @param string $id
     * @return JsonResponse
     */
    public function delete(string $id): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $message = $this->getDoctrine()->getRepository(Message::class)->find($id);

        if (null === $message) {
            $response = $this->json([
                'success' => false,
                'status' => 'Message ' . $id . ' doesn\'t exist.'
            ]);
            $response->setStatusCode(403);
            $response->headers->set('Access-Control-Allow-Origin', '*');
            return $response;
        }
        $entityManager->remove($message);
        $entityManager->flush();
        $message = $this->getDoctrine()->getRepository(Message::class)->find($id);
        $response = $this->json([
            'success' => true,
            'status' => 'Message ' . $id . ' deleted.',
            'message' => $message
        ]);
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }
}
