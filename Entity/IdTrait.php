<?php

declare(strict_types=1);

namespace Linderp\SuluBaseBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Provides a standard auto-incrementing integer ID property.
 *
 * Use this trait for entities that need a simple numeric primary key.
 */
trait IdTrait
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    protected ?int $id = null;

    /**
     * Returns the entity's ID or null if not yet persisted.
     */
    public function getId(): ?int
    {
        return $this->id;
    }
}
