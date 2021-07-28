<?php

namespace App\Models\ORM\Problems;

use Fykosak\NetteORM\AbstractModel;

class TagModel extends AbstractModel
{
    public function getLocalizedData(string $lang): ?TagLocalizedDataModel
    {
        $row = $this->related('tag_localized_data')
            ->where('language', $lang)
            ->fetch();
        return $row ? TagLocalizedDataModel::createFromActiveRow($row) : null;
    }
}
