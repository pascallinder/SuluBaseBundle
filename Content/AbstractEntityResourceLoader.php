<?php

declare(strict_types=1);

namespace Linderp\SuluBaseBundle\Content;

use Doctrine\ORM\EntityManagerInterface;
use Sulu\Content\Application\ContentResolver\Value\ContentView;
use Sulu\Content\Application\ResourceLoader\Loader\ResourceLoaderInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * Shared resource-loader implementation for localized, enableable Doctrine entities.
 */
abstract readonly class AbstractEntityResourceLoader implements ResourceLoaderInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private PropertyAccessorInterface $propertyAccessor,
    ) {}

    public function load(array $ids, ?string $locale, array $params = []): array
    {
        $entityClass = $this->getEntityClass();
        $repository = $this->entityManager->getRepository($entityClass);
        $result = [];

        foreach ($ids as $id) {
            $entity = $repository->find((int) $id);
            if (!$entity instanceof $entityClass || !$this->isEntityEnabled($entity)) {
                continue;
            }

            $this->applyLocale($entity, $locale ?? 'en');
            $result[$id] = ContentView::create($this->mapProperties($entity, $params), []);
        }

        return $result;
    }

    /** @return class-string */
    abstract protected function getEntityClass(): string;

    abstract protected function isEntityEnabled(object $entity): bool;

    abstract protected function applyLocale(object $entity, string $locale): void;

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    private function mapProperties(object $entity, array $params): array
    {
        $properties = $params['properties'] ?? [];
        if (!\is_array($properties)) {
            return [];
        }

        $data = [];
        foreach ($properties as $output => $propertyPath) {
            if (!\is_string($output) || !\is_string($propertyPath) || !$this->propertyAccessor->isReadable($entity, $propertyPath)) {
                continue;
            }

            $data[$output] = $this->propertyAccessor->getValue($entity, $propertyPath);
        }

        return $data;
    }
}
