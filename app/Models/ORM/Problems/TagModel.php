<?php

declare(strict_types=1);

namespace App\Models\ORM\Problems;

use Fykosak\NetteORM\Model;

class TagModel extends Model
{
    public function getLocalizedData(string $lang): ?TagLocalizedDataModel
    {
        $row = $this->related('tag_localized_data')
            ->where('language', $lang)
            ->fetch();
        return $row ? TagLocalizedDataModel::createFromActiveRow($row) : null;
    }
}
