<?php

namespace Linderp\SuluBaseBundle\Repository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\QueryBuilder;
use Linderp\SuluBaseBundle\Entity\LocaleTrait;
use Sulu\Component\SmartContent\Orm\DataProviderRepositoryInterface;
use Sulu\Component\SmartContent\Orm\DataProviderRepositoryTrait;

/**
 * @template T of LocaleTrait
 * @extends ServiceEntityRepository<T>
 */
abstract class LocaleRepositoryUtil extends ServiceEntityRepository implements DataProviderRepositoryInterface
{
    use DataProviderRepositoryTrait {
        DataProviderRepositoryTrait::findByFilters as parentFindByFilters;
    }
    /**
     * @return T
     */
    public function create(string $locale)
    {
        $class = $this->getClassName();
        $object = new $class();
        $object->setLocale($locale);
        return $object;
    }

    /**
     * @throws ORMException
     */
    public function removeById(int $id): void
    {
        /** @var object $event */
        $object = $this->getEntityManager()->getReference(
            $this->getClassName(),
            $id,
        );
        $this->getEntityManager()->remove($object);
    }
    /**
     * @param T $wardrobe
     */
    public function save($wardrobe): void
    {
        $this->getEntityManager()->persist($wardrobe);
    }
    /**
     * @return ?T
     */
    public function findById(int $id, string $locale)
    {
        $object = $this->find($id);
        if ($object === null) {
            return null;
        }
        $object->setLocale($locale);
        return $object;
    }
    /**
     * @return T[]
     */
    public function findAllLocalized(string $locale): array
    {
        $objects = $this->findAll();
        foreach ($objects as $object){
            $object->setLocale($locale);
        }
        return $objects;
    }
    /**
     * @return T[]
     */
    public function findByFilters($filters, $page, $pageSize, $limit, $locale, $options = []): array
    {
        $entities = $this->parentFindByFilters($filters, $page, $pageSize, $limit, $locale, $options);
        return \array_map(
            function ($entity) use ($locale) {
                $entity->setLocale($locale);
                return $entity;
            },
            $entities
        );
    }
    protected function appendJoins(QueryBuilder $queryBuilder, $alias, $locale): void
    {
        $this->appendSortByJoins($queryBuilder,$alias,$locale);
    }
    /**
     * @param mixed[] $options
     *
     * @return string[]
     */
    protected abstract function append(QueryBuilder $queryBuilder, string $alias, string $locale, $options = []): array;

    protected abstract function appendSortByJoins(QueryBuilder $queryBuilder, string $alias, string $locale): void;

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}