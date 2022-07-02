<?php

declare(strict_types=1);

namespace App\Models\ORM\Problems;

use Fykosak\NetteORM\Service;

/**
 * @method DirectoryModel|null findByPrimary($key)
 */
class DirectoryService extends Service
{
    public const ROOT_DIR = '_root';

    public function findRoot(): ?DirectoryModel
    {
        return $this->findDirByCode(self::ROOT_DIR);
    }

    public function findDirByCode(string $code): ?DirectoryModel
    {
        /** @var DirectoryModel $dir */
        $dir = $this->getTable()->where('code', $code)->fetch();
        return $dir;
    }
}
