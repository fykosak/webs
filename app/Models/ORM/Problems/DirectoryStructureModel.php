<?php

declare(strict_types=1);

namespace App\Models\ORM\Problems;

use Fykosak\NetteORM\AbstractModel;
use Nette\Database\Table\ActiveRow;

/**
 * @property-read int id
 * @property-read int parent_directory_id
 * @property-read int child_directory_id
 * @property-read ActiveRow parent_directory
 * @property-read ActiveRow child_directory
 */
class DirectoryStructureModel extends AbstractModel
{
    public function getChildDirectory(): DirectoryModel
    {
        return DirectoryModel::createFromActiveRow($this->child_directory);
    }

    public function getParentDirectory(): DirectoryModel
    {
        return DirectoryModel::createFromActiveRow($this->parent_directory);
    }
}
