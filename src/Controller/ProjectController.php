<?php

namespace App\Controller;

use App\Entity\Project;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProjectController
 * @package App\Controller
 * @Route("/projects", name="projects_")
 */
class ProjectController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $projectsRepository = $this->getDoctrine()->getRepository(Project::class);
        $projectsList = $projectsRepository->findAll();
        $response = $this->json([
            'success' => true,
            'status' => 'Projects retrieved.',
            'projects' => $projectsList
        ]);

        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }

    /**
     * @Route("/", name="create", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $requestData = $request->toArray();
        $entityManager = $this->getDoctrine()->getManager();
        $project = new Project();

        if (!isset($requestData['name']) or !isset($requestData['description']) or
            !isset($requestData['link']) or !isset($requestData['image'])) {
            $response = $this->json([
                'success' => false,
                'status' => 'Project body is incomplete.'
            ]);
            $response->setStatusCode(403);
            $response->headers->set('Access-Control-Allow-Origin', '*');
            return $response;
        }
        $project->setName($requestData['name']);
        $project->setDescription($requestData['description']);
        $project->setLink($requestData['link']);
        $project->setImage($requestData['image']);
        $entityManager->persist($project);
        $entityManager->flush();
        $response = $this->json([
            'success' => true,
            'status' => 'Project created.',
            'project' => $project
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
        $projectsList = $entityManager->getRepository(Project::class)->findAll();

        foreach ($projectsList as $project) {
            $entityManager->remove($project);
        }
        $entityManager->flush();
        $projectsList = $entityManager->getRepository(Project::class)->findAll();
        $response = $this->json([
            'success' => true,
            'status' => 'Projects destroyed.',
            'projects' => $projectsList
        ]);
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $project = $this->getDoctrine()->getRepository(Project::class)->find($id);

        if (null === $project) {
            $response = $this->json([
                'success' => false,
                'status' => 'Project ' . $id . ' doesn\'t exist.',
            ]);
            $response->setStatusCode(403);
            $response->headers->set('Access-Control-Allow-Origin', '*');
            return $response;
        }
        $response = $this->json([
            'success' => true,
            'status' => 'Project retrieved.',
            'project' => $project
        ]);
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }

    /**
     * @Route("/{id}", name="edit", methods={"PUT"})
     * @IsGranted("ROLE_ADMIN")
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     */
    public function edit(string $id, Request $request): JsonResponse
    {
        $requestData = $request->toArray();
        $entityManager = $this->getDoctrine()->getManager();
        $project = $entityManager->getRepository(Project::class)->find($id);

        if (isset($requestData['name'])) {
            $project->setName($requestData['name']);
        }
        if (isset($requestData['description'])) {
            $project->setDescription($requestData['description']);
        }
        if (isset($requestData['link'])) {
            $project->setLink($requestData['link']);
        }
        if (isset($requestData['image'])) {
            $project->setImage($requestData['image']);
        }
        $entityManager->persist($project);
        $entityManager->flush();
        $project = $entityManager->getRepository(Project::class)->find($id);
        $response = $this->json([
            'success' => true,
            'status' => 'Project updated.',
            'project' => $project
        ]);
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
        $project = $this->getDoctrine()->getRepository(Project::class)->find($id);

        if (null === $project) {
            $response = $this->json([
                'success' => false,
                'status' => 'Project ' . $id . ' doesn\'t exist.'
            ]);
            $response->setStatusCode(403);
            $response->headers->set('Access-Control-Allow-Origin', '*');
            return $response;
        }
        $entityManager->remove($project);
        $entityManager->flush();
        $project = $this->getDoctrine()->getRepository(Project::class)->find($id);
        $response = $this->json([
            'success' => true,
            'status' => 'Project ' . $id . ' deleted.',
            'project' => $project
        ]);
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }
}
