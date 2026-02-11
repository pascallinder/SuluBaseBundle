<?php

declare(strict_types=1);

namespace Linderp\SuluBaseBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;

/**
 * Base repository utility providing common CRUD operations.
 *
 * Extends Doctrine's ServiceEntityRepository with convenience methods
 * for creating, saving, and removing entities.
 *
 * @template T of object
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
    public function create(): object
    {
        $class = $this->getClassName();

        return new $class();
    }

    /**
     * Removes an entity by ID using a reference (no SELECT query).
     *
     * More efficient than loading the full entity before removal.
     *
     * @throws ORMException
     */
    public function removeById(int $id): void
    {
        $entity = $this->getEntityManager()->getReference(
            $this->getClassName(),
            $id,
        );
        $this->getEntityManager()->remove($entity);
    }

    /**
     * Persists an entity to the database.
     *
     * Does not flush; call {@see flush()} to commit changes.
     *
     * @param T $entity
     */
    public function save(object $entity): void
    {
        $this->getEntityManager()->persist($entity);
    }

    /**
     * Flushes all pending changes to the database.
     */
    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}
