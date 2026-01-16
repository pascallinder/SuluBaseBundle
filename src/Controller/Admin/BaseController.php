<?php
namespace Linderp\SuluBaseBundle\Controller\Admin;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @template T
 *
 */
abstract class BaseController extends AbstractController
{
    /**
     *  @param T $entity
     */
    protected abstract function getDataForEntity($entity, Request $request): array;
    /**
     *  @param T $entity
     */
    protected abstract function mapDataToEntity(array $data, $entity, Request $request): void;

    protected abstract function load(int $id, Request $request);
    /**
     * @return T
     */
    protected abstract function create(Request $request);

    /**
     *  @param T $entity
     */
    protected abstract function save($entity): void;

    protected abstract function remove(int $id): void;

    /**
     *  @param T $entity
     */
    protected abstract function triggerSwitch(Request $request,string $action, $entity);

    protected function handleGetByIdRequest(int $id, Request $request): JsonResponse
    {
        $entity = $this->load($id, $request);
        if ($entity === null) {
            throw new NotFoundHttpException();
        }
        return $this->respondWithEntity($entity, $request);
    }
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

    protected function handlePostRequest(Request $request): JsonResponse
    {
        $event = $this->create($request);

        $data = $request->toArray();
        $this->mapDataToEntity($data, $event, $request);
        $this->save($event);

        return $this->respondWithEntity($event, $request);
    }

    protected function handlePostTriggerRequest(int $id,Request $request): JsonResponse
    {
        $entity = $this->load($id, $request);
        if ($entity === null) {
            throw new NotFoundHttpException();
        }
        try {
            $this->triggerSwitch($request,$request->query->get('action'),$entity);
        }catch (\Throwable $throwable){
            return $this->json(['error'=> $throwable->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $this->save($entity);
        return $this->respondWithEntity($entity, $request);
    }
    protected function handleDeleteRequest(int $id): JsonResponse
    {
        $this->remove($id);
        return $this->json(null, 204);
    }
    protected function respondWithEntity(object $entity, Request $request, int $status = 200): JsonResponse
    {
        return $this->json($this->getDataForEntity($entity, $request), $status);
    }
}