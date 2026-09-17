<?php

declare(strict_types=1);

namespace Linderp\SuluBaseBundle\Tests\Subscriber;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\Persistence\Mapping\ClassMetadata;
use Linderp\SuluBaseBundle\Subscriber\EntityStringTrimSubscriber;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

final class EntityStringTrimSubscriberTest extends TestCase
{
    public function testTrimsMappedPrivateFieldsOnPersistedDoctrineProxy(): void
    {
        $entity = new class extends EntityStringTrimSubscriberTestEntity {};
        $entity->setName('  Quartierzentrum Oerlikon  ');
        [, $objectManager] = $this->createMetadata(['name']);

        (new EntityStringTrimSubscriber())->prePersist(
            new PrePersistEventArgs($entity, $objectManager),
        );

        self::assertSame('Quartierzentrum Oerlikon', $entity->getName());
    }

    public function testTrimsMappedPrivateFieldsInTheUpdateChangeSet(): void
    {
        $entity = new class extends EntityStringTrimSubscriberTestEntity {};
        $entity->setName('  Quartierzentrum Oerlikon  ');
        [, $objectManager] = $this->createMetadata(['name']);
        $changeSet = ['name' => [null, '  Quartierzentrum Oerlikon  ']];
        $preUpdateEvent = new PreUpdateEventArgs($entity, $objectManager, $changeSet);

        (new EntityStringTrimSubscriber())->preUpdate($preUpdateEvent);

        self::assertSame('Quartierzentrum Oerlikon', $entity->getName());
        self::assertSame('Quartierzentrum Oerlikon', $preUpdateEvent->getNewValue('name'));
    }

    /**
     * @param list<string> $fieldNames
     *
     * @return array{0: ClassMetadata, 1: EntityManagerInterface}
     */
    private function createMetadata(array $fieldNames): array
    {
        $metadata = $this->createMock(ClassMetadata::class);
        $metadata->method('getFieldNames')->willReturn($fieldNames);
        $metadata->method('hasField')->willReturnCallback(
            static fn(string $fieldName): bool => in_array($fieldName, $fieldNames, true),
        );
        $metadata->method('getReflectionClass')->willReturn(new ReflectionClass(EntityStringTrimSubscriberTestEntity::class));

        $objectManager = $this->createMock(EntityManagerInterface::class);
        $objectManager->method('getClassMetadata')->willReturn($metadata);

        return [$metadata, $objectManager];
    }
}

class EntityStringTrimSubscriberTestEntity
{
    private ?string $name = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
