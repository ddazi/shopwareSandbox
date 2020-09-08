<?php declare(strict_types=1);

namespace SandBoxPlugin\Core\Content\SandBoxPlugin;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void              add(SandBoxEntity $entity)
 * @method void              set(string $key, SandBoxEntity $entity)
 * @method SandBoxEntity[]    getIterator()
 * @method SandBoxEntity[]    getElements()
 * @method SandBoxEntity|null get(string $key)
 * @method SandBoxEntity|null first()
 * @method SandBoxEntity|null last()
 */
class SandBoxCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return SandBoxEntity::class;
    }
}
