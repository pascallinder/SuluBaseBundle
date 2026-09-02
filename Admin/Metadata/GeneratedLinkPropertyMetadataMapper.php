<?php

declare(strict_types=1);

namespace Linderp\SuluBaseBundle\Admin\Metadata;

use Sulu\Bundle\AdminBundle\Metadata\FormMetadata\FieldMetadata;
use Sulu\Bundle\AdminBundle\Metadata\SchemaMetadata\PropertyMetadata;
use Sulu\Bundle\AdminBundle\Metadata\SchemaMetadata\PropertyMetadataMapper\TextPropertyMetadataMapper;
use Sulu\Bundle\AdminBundle\Metadata\SchemaMetadata\PropertyMetadataMapperInterface;

final readonly class GeneratedLinkPropertyMetadataMapper implements PropertyMetadataMapperInterface
{
    public function __construct(private TextPropertyMetadataMapper $textPropertyMetadataMapper) {}

    public function mapPropertyMetadata(FieldMetadata $fieldMetadata): PropertyMetadata
    {
        return $this->textPropertyMetadataMapper->mapPropertyMetadata($fieldMetadata);
    }
}
