<?php

namespace App\Models\ORM\Problems;

use Fykosak\NetteORM\AbstractService;

/**
 * @method DirectoryModel|null findByPrimary($key)
 */
class DirectoryService extends AbstractService
{
    public const ROOT_DIR = '_root';

    public function findRoot(): ?DirectoryModel
    {
        return $this->findDirByCode(self::ROOT_DIR);
    }

    public function findDirByCode(string $code): ?DirectoryModel
    {
        return $this->getTable()->where('code', $code)->fetch();
    }
}
