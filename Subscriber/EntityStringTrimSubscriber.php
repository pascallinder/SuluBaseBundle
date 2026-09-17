<?php

declare(strict_types=1);

namespace Linderp\SuluBaseBundle\Subscriber;

use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\Persistence\Mapping\ClassMetadata;
use ReflectionProperty;

final class EntityStringTrimSubscriber
{
    public function prePersist(PrePersistEventArgs $event): void
    {
        $entity = $event->getObject();
        $metadata = $event->getObjectManager()->getClassMetadata($entity::class);

        foreach ($metadata->getFieldNames() as $fieldName) {
            $property = $metadata->getReflectionClass()->getProperty($fieldName);

            if (!$property->isInitialized($entity)) {
                continue;
            }

            $this->trimProperty($property, $entity);
        }
    }

    public function preUpdate(PreUpdateEventArgs $event): void
    {
        $entity = $event->getObject();
        $metadata = $event->getObjectManager()->getClassMetadata($entity::class);

        foreach ($event->getEntityChangeSet() as $fieldName => $change) {
            if (!$metadata->hasField($fieldName)) {
                continue;
            }

            $newValue = $event->getNewValue($fieldName);

            if (!is_string($newValue)) {
                continue;
            }

            $trimmedValue = trim($newValue);

            if ($trimmedValue === $newValue) {
                continue;
            }

            $property = $metadata->getReflectionClass()->getProperty($fieldName);
            $property->setValue($entity, $trimmedValue);
            $event->setNewValue($fieldName, $trimmedValue);
        }
    }

    private function trimProperty(ReflectionProperty $property, object $entity): void
    {
        $value = $property->getValue($entity);

        if (!is_string($value)) {
            return;
        }

        $trimmedValue = trim($value);

        if ($trimmedValue !== $value) {
            $property->setValue($entity, $trimmedValue);
        }
    }
}
