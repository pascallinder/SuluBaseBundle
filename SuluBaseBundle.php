<?php

declare(strict_types=1);

namespace Linderp\SuluBaseBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * SuluBaseBundle - Reusable abstractions for Sulu CMS admin interfaces.
 *
 * Provides:
 * - AdminCrud system for auto-generating list/form views
 * - BaseController template for REST CRUD operations
 * - Repository utilities for common persistence patterns
 * - Entity traits for ID and locale properties
 * - Custom content types (color picker, range picker)
 */
final class SuluBaseBundle extends Bundle {}
