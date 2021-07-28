<?php

namespace App\Models\ORM\Problems;

use Fykosak\NetteORM\AbstractService;

/**
 * @method DirectoryModel|null findByPrimary($key)
 */
class DirectoryService extends AbstractService
{

    public function findDirByPath(string $path, string $rootDir = '_root'): ?DirectoryModel
    {
        $parts = explode('/', $path);

        $parentDir = $this->findDirByCode($rootDir);
        foreach ($parts as $part) {
            $row = $this->explorer->query('SELECT d.id
FROM directory_structure
    JOIN directory d ON d.id = directory_structure.child_directory_id
WHERE parent_directory_id = ? AND code=?', $parentDir->id, $part)->fetch();

            if (is_null($row)) {
                return null;
            }
            $parentDir = $row;
        }
        return $this->findByPrimary($parentDir->id);
    }

    public function findDirByCode(string $code): ?DirectoryModel
    {
        return $this->getTable()->where('code', $code)->fetch();
    }
}
