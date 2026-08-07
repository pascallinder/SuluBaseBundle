<?php

declare(strict_types=1);

namespace Linderp\SuluBaseBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\QueryBuilder;
use Linderp\SuluBaseBundle\Entity\LocaleTrait;

/**
 * Repository utility for locale-aware entities.
 *
 * Extends ServiceEntityRepository with locale-specific query methods.
 *
 * Requires entities to use {@see LocaleTrait}.
 *
 * @template T of object
 * @extends ServiceEntityRepository<T>
 */
abstract class LocaleRepositoryUtil extends ServiceEntityRepository
{
    /**
     * Creates a new entity instance with the specified locale.
     *
     * @return T
     */
    public function create(string $locale): object
    {
        $class = $this->getClassName();
        $object = new $class();
        $object->setLocale($locale);

        return $object;
    }

    /**
     * Removes an entity by ID using a reference (no SELECT query).
     *
     * @throws ORMException
     */
    public function removeById(int $id): void
    {
        $object = $this->getEntityManager()->getReference(
            $this->getClassName(),
            $id,
        );
        $this->getEntityManager()->remove($object);
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
     * Finds an entity by ID and sets its locale.
     *
     * @return T|null
     */
    public function findById(int $id, string $locale): ?object
    {
        $object = $this->find($id);
        if ($object === null) {
            return null;
        }

        $object->setLocale($locale);

        return $object;
    }

    /**
     * Finds all entities and sets their locale.
     *
     * @return list<T>
     */
    public function findAllLocalized(string $locale): array
    {
        $objects = $this->findAll();
        foreach ($objects as $object) {
            $object->setLocale($locale);
        }

        return $objects;
    }

    /**
     * Appends joins to the query builder.
     *
     * Override to add custom joins for filtering/sorting.
     */
    protected function appendJoins(QueryBuilder $queryBuilder, $alias, $locale): void
    {
        $this->appendSortByJoins($queryBuilder, $alias, $locale);
    }

    /**
     * Appends custom fields to the query builder.
     *
     * Used by Sulu's data provider for smart content.
     *
     * @param array<string, mixed> $options
     * @return list<string> Field names added to the query
     */
    abstract protected function append(QueryBuilder $queryBuilder, string $alias, string $locale, $options = []): array;

    /**
     * Appends joins required for sorting.
     *
     * Override to add joins needed by sortable fields.
     */
    abstract protected function appendSortByJoins(QueryBuilder $queryBuilder, string $alias, string $locale): void;

    /**
     * Flushes all pending changes to the database.
     */
    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}
