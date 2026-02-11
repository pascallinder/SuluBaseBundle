<?php

declare(strict_types=1);

namespace Linderp\SuluBaseBundle\Common;

use Sulu\Component\Rest\ListBuilder\Doctrine\DoctrineListBuilder;
use Sulu\Component\Rest\ListBuilder\Doctrine\DoctrineListBuilderFactoryInterface;
use Sulu\Component\Rest\ListBuilder\Doctrine\FieldDescriptor\DoctrineFieldDescriptor;
use Sulu\Component\Rest\ListBuilder\ListRestHelperInterface;
use Sulu\Component\Rest\ListBuilder\Metadata\FieldDescriptorFactoryInterface;
use Sulu\Component\Rest\ListBuilder\PaginatedRepresentation;
use Sulu\Component\Rest\RestHelperInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Factory for creating Doctrine-based list representations.
 *
 * Wraps Sulu's list builder infrastructure to provide paginated, sortable,
 * and filterable lists for admin interfaces.
 *
 * Handles:
 * - Field descriptor lookup from metadata
 * - Request parameter extraction (pagination, sorting, filtering)
 * - Invalid sort field validation
 * - Custom ID ordering for specific list requests
 */
readonly class DoctrineListRepresentationFactory
{
    public function __construct(
        private RestHelperInterface $restHelper,
        private ListRestHelperInterface $listRestHelper,
        private DoctrineListBuilderFactoryInterface $listBuilderFactory,
        private FieldDescriptorFactoryInterface $fieldDescriptorFactory,
        private RequestStack $requestStack
    ) {}

    /**
     * Creates a paginated list representation.
     *
     * @param string $resourceKey Resource identifier for metadata lookup
     * @param array<string, string> $filters Field filters to apply
     * @param array<string, string|int|null> $parameters Query parameters for the list builder
     * @param list<string> $includedFields Additional fields to include in the response
     */
    public function createDoctrineListRepresentation(
        string $resourceKey,
        array $filters = [],
        array $parameters = [],
        array $includedFields = [],
    ): PaginatedRepresentation {
        /** @var array<string, DoctrineFieldDescriptor> $fieldDescriptors */
        $fieldDescriptors = $this->fieldDescriptorFactory->getFieldDescriptors($resourceKey);

        /** @var DoctrineListBuilder $listBuilder */
        $listBuilder = $this->listBuilderFactory->create($fieldDescriptors['id']->getEntityName());

        // Validate sortBy parameter to prevent errors on invalid field names
        $currentRequest = $this->requestStack->getCurrentRequest();
        if ($currentRequest !== null) {
            $sortBy = $currentRequest->get('sortBy');
            if ($sortBy !== null && !array_key_exists($sortBy, $fieldDescriptors)) {
                $currentRequest->attributes->set('sortBy', null);
            }
        }

        $this->restHelper->initializeListBuilder($listBuilder, $fieldDescriptors);

        foreach ($parameters as $key => $value) {
            $listBuilder->setParameter($key, $value);
        }

        foreach ($filters as $key => $value) {
            $listBuilder->where($fieldDescriptors[$key], $value);
        }

        foreach ($includedFields as $field) {
            $listBuilder->addSelectField($fieldDescriptors[$field]);
        }

        $items = $listBuilder->execute();

        // Sort items by requested ID order if specific IDs were requested
        $requestedIds = $this->listRestHelper->getIds();
        if ($requestedIds !== null) {
            $idPositions = array_flip($requestedIds);
            usort($items, static fn(array $a, array $b): int => $idPositions[$a['id']] - $idPositions[$b['id']]);
        }

        return new PaginatedRepresentation(
            $items,
            $resourceKey,
            (int) $listBuilder->getCurrentPage(),
            (int) $listBuilder->getLimit(),
            (int) $listBuilder->count(),
        );
    }
}
