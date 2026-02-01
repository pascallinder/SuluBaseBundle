<?php

namespace Linderp\SuluBaseBundle\Repository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;

/**
 * @template T
 * @extends ServiceEntityRepository<T>
 */
abstract class BaseRepositoryUtil extends ServiceEntityRepository
{
    /**
     * Creates a new entity instance.
     *
     * Override if your entity requires constructor arguments.
     *
     * @return T
     */
    public function create()
    {
        $class = $this->getClassName();
        return new $class();
    }

    /**
     * @throws ORMException
     */
    public function removeById(int $id): void
    {
        $entity = $this->getEntityManager()->getReference(
            $this->getClassName(),
            $id,
        );
        $this->getEntityManager()->remove((object)$entity);
    }

    /**
     * @param T $entity
     */
    public function save($entity): void
    {
        $this->getEntityManager()->persist($entity);
    }
    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}