<?php

namespace App\Models\ORM\Problems;

use Fykosak\NetteORM\AbstractModel;
use Nette\Database\Table\GroupedSelection;

/**
 * @property-read int id
 * @property-read string name
 * @property-read string code
 */
class DirectoryModel extends AbstractModel
{

    public function getProblems(): GroupedSelection
    {
        return $this->related('problem');
    }

}
