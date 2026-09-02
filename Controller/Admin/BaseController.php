<?php

declare(strict_types=1);

namespace Linderp\SuluBaseBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Generic REST controller template for Sulu admin CRUD operations.
 *
 * Provides standardized request handling for GET/POST/PUT/DELETE operations.
 * Subclasses implement entity-specific serialization, persistence, and business logic.
 *
 * @template T of object
 */
abstract class BaseController extends AbstractController
{
    /**
     * Serializes an entity to an array for API response.
     *
     * @param T $entity
     * @return array<string, mixed>
     */
    abstract protected function getDataForEntity($entity, Request $request): array;

    /**
     * Maps request data to entity properties.
     *
     * @param array<string, mixed> $data
     * @param T $entity
     */
    abstract protected function mapDataToEntity(array $data, $entity, Request $request): void;

    /**
     * Loads an entity by ID or returns null if not found.
     *
     * @return T|null
     */
    abstract protected function load(int $id, Request $request);

    /**
     * Creates a new entity instance.
     *
     * May return null if the subclass doesn't support entity creation via POST.
     *
     * @return T|null
     */
    abstract protected function create(Request $request);

    /**
     * Persists an entity to the database.
     *
     * @param T $entity
     */
    abstract protected function save($entity): void;

    /**
     * Removes an entity from the database.
     */
    abstract protected function remove(int $id): void;

    /**
     * Handles enable/disable toggle switch actions.
     *
     * @param T $entity
     */
    abstract protected function triggerSwitch(Request $request, string $action, $entity): void;

    /**
     * Handles GET request for a single entity.
     *
     * @throws NotFoundHttpException if entity not found
     */
    protected function handleGetByIdRequest(int $id, Request $request): JsonResponse
    {
        $entity = $this->load($id, $request);
        if ($entity === null) {
            throw new NotFoundHttpException();
        }

        return $this->respondWithEntity($entity, $request);
    }

    /**
     * Handles PUT request to update an existing entity.
     *
     * @throws NotFoundHttpException if entity not found
     */
    protected function handlePutRequest(int $id, Request $request): JsonResponse
    {
        $entity = $this->load($id, $request);
        if ($entity === null) {
            throw new NotFoundHttpException();
        }

        $data = $request->toArray();
        $this->mapDataToEntity($data, $entity, $request);
        $this->save($entity);

        return $this->respondWithEntity($entity, $request);
    }

    /**
     * Handles POST request to create a new entity.
     */
    protected function handlePostRequest(Request $request): JsonResponse
    {
        $entity = $this->create($request);

        if ($entity === null) {
            return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
        }

        $data = $request->toArray();
        $this->mapDataToEntity($data, $entity, $request);
        $this->save($entity);

        return $this->respondWithEntity($entity, $request);
    }

    /**
     * Handles POST trigger request for enable/disable toggle.
     *
     * @throws NotFoundHttpException if entity not found
     */
    protected function handlePostTriggerRequest(int $id, Request $request): JsonResponse
    {
        $entity = $this->load($id, $request);
        if ($entity === null) {
            throw new NotFoundHttpException();
        }

        $action = $request->query->getString('action');
        try {
            $this->triggerSwitch($request, $action, $entity);
        } catch (\Throwable $throwable) {
            return $this->json(['error' => $throwable->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $this->save($entity);

        return $this->respondWithEntity($entity, $request);
    }

    /**
     * Handles DELETE request to remove an entity.
     */
    protected function handleDeleteRequest(int $id): JsonResponse
    {
        $this->remove($id);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Creates a JSON response with the serialized entity data.
     *
     * @param T $entity
     */
    protected function respondWithEntity(object $entity, Request $request, int $status = Response::HTTP_OK): JsonResponse
    {
        return $this->json($this->getDataForEntity($entity, $request), $status);
    }
}
