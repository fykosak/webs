<?php

declare(strict_types=1);

namespace App\Models\ORM\Problems;

use Fykosak\NetteORM\Model;

class TagLocalizedDataModel extends Model
{
    public function getLocalizedData(string $lang): ?TopicLocalizedDataModel
    {
        $row = $this->related('topic_localized_data')
            ->where('language', $lang)
            ->fetch();
        return $row ? TopicLocalizedDataModel::createFromActiveRow($row) : null;
    }
}
