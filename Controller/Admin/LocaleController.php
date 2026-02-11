<?php

declare(strict_types=1);

namespace Linderp\SuluBaseBundle\Controller\Admin;

use Linderp\SuluBaseBundle\Repository\LocaleRepositoryUtil;
use Symfony\Component\HttpFoundation\Request;

/**
 * Base controller for locale-aware entities.
 *
 * Provides implementations of {@see BaseController} abstract methods
 * using {@see LocaleRepositoryUtil}. Automatically extracts locale from
 * the request query parameter.
 *
 * @template T of object
 * @extends BaseController<T>
 */
abstract class LocaleController extends BaseController
{
    /**
     * @param LocaleRepositoryUtil<T> $localeRepositoryUtil
     */
    public function __construct(
        private readonly LocaleRepositoryUtil $localeRepositoryUtil
    ) {}

    /**
     * @return T|null
     */
    protected function load(int $id, Request $request)
    {
        return $this->localeRepositoryUtil->findById($id, $this->getLocale($request));
    }

    /**
     * @return T
     */
    protected function create(Request $request)
    {
        return $this->localeRepositoryUtil->create($this->getLocale($request));
    }

    /**
     * @param T $entity
     */
    protected function save($entity): void
    {
        $this->localeRepositoryUtil->save($entity);
        $this->localeRepositoryUtil->flush();
    }

    protected function remove(int $id): void
    {
        $this->localeRepositoryUtil->removeById($id);
        $this->localeRepositoryUtil->flush();
    }

    /**
     * Extracts locale from request query parameter.
     *
     * Defaults to 'en' if not present.
     */
    protected function getLocale(Request $request): string
    {
        return $request->query->getString('locale', 'en');
    }
}
