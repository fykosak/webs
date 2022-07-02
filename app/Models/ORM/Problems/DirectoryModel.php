<?php

declare(strict_types=1);

namespace App\Models\ORM\Problems;

use Fykosak\NetteORM\Model;
use Nette\Database\Table\GroupedSelection;

/**
 * @property-read int id
 * @property-read string name
 * @property-read string code
 */
class DirectoryModel extends Model
{

    /**
     * @return ProblemModel[]
     */
    public function getProblems(bool $recursive = false): array
    {
        $problems = [];
        if ($recursive) {
            foreach ($this->findChilds() as $row) {
                $structure = DirectoryStructureModel::createFromActiveRow($row);
                $problems = [...$problems, ...$structure->getChildDirectory()->getProblems(true)];
            }
        }
        foreach ($this->related('problem') as $row) {
            $problems[] = ProblemModel::createFromActiveRow($row);
        }
        return $problems;
    }

    public function findChilds(): GroupedSelection
    {
        return $this->related('directory_structure', 'parent_directory_id');
    }

    public function findChildByPath(string $path): ?DirectoryModel
    {
        $parts = explode('/', $path);
        $parentDir = $this;
        foreach ($parts as $part) {
            $row = $parentDir->findChilds()
                ->where('child_directory.code', $part)
                ->fetch();
            if (is_null($row)) {
                return null;
            }
            $parentDir = DirectoryStructureModel::createFromActiveRow($row)->getChildDirectory();
        }
        return $parentDir;
    }
}
