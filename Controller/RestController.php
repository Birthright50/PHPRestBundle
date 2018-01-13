<?php

namespace Birthright\SuperRestBundle\Controller;


use Birthright\SuperRestBundle\Service\FileService;
use Birthright\SuperRestBundle\Service\RestService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class RestController extends Controller
{
    private $fileService;


    /**
     * RestController constructor.
     */
    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function findAll(string $entity)
    {
        $restService = $this->getRestService($this->fileService->findService($entity));
        $encoded = json_encode($restService->findAll());
        return new JsonResponse($encoded);
    }


    public function find(string $entity, $id)
    {
        $restService = $this->getRestService($this->fileService->findService($entity));
        $encoded = json_encode($restService->find($id));
        return new JsonResponse($encoded);
    }


    public function delete(string $entity, $id)
    {
        $restService = $this->getRestService($this->fileService->findService($entity));
        try {
            $restService->delete($id);
            return new JsonResponse(null, 200);
        } catch (\Exception $e) {
            return new JsonResponse('Something went wrong', 200);
        }
    }


    public function deleteAll(string $entity)
    {
        $restService = $this->getRestService($this->fileService->findService($entity));
        try {
            $restService->delete($restService->deleteAll());
            return new JsonResponse(null, 200);
        } catch (\Exception $e) {
            return new JsonResponse('Something went wrong', 400);
        }
    }


    public function update(string $entity,   $id, Request $request)
    {
        if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
            $data = json_decode($request->getContent());
            //$request->request->replace(is_array($data) ? $data : []);
            $restService = $this->getRestService($this->fileService->findService($entity));
            try {
                $restService->update($id, $data);
                return new JsonResponse('OK', 201);
            } catch (\Exception $e) {
                return new JsonResponse('Invalid request', 400);
            }
        } else {
            return new JsonResponse('Invalid request', 400);
        }
    }


    public function save(string $entity,  Request $request)
    {
        if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
            $data = json_decode($request->getContent());
            //$request->request->replace(is_array($data) ? $data : []);
             $restService = $this->getRestService($this->fileService->findService($entity));
            try {
                $restService->save($data);
                return new JsonResponse('OK', 201);
            } catch (\Exception $e) {
                return new JsonResponse('Invalid request', 400);
            }
        } else {
            return new JsonResponse('Invalid request', 400);
        }
    }


    private function getRestService(string $serviceClass): RestService
    {
        return $this->get($serviceClass);
    }
}