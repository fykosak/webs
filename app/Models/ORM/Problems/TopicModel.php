<?php

namespace App\Models\ORM\Problems;

use Fykosak\NetteORM\AbstractModel;

class TopicModel extends AbstractModel
{
    public function getLocalizedData(string $lang): ?ProblemLocalizedDataModel
    {
        $row = $this->related('problem_localized_data')
            ->where('language', $lang)
            ->fetch();
        return $row ? ProblemLocalizedDataModel::createFromActiveRow($row) : null;
    }
}
