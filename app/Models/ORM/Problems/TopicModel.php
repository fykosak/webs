<?php

declare(strict_types=1);

namespace App\Models\ORM\Problems;

use Fykosak\NetteORM\Model;

class TopicModel extends Model
{
    public function getLocalizedData(string $lang): ?ProblemLocalizedDataModel
    {
        $row = $this->related('problem_localized_data')
            ->where('language', $lang)
            ->fetch();
        return $row ? ProblemLocalizedDataModel::createFromActiveRow($row) : null;
    }
}
