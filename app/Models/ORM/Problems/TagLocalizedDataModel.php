<?php
declare(strict_types=1);

namespace App\Models\ORM\Problems;

use Fykosak\NetteORM\AbstractModel;

class TagLocalizedDataModel extends AbstractModel
{
    public function getLocalizedData(string $lang): ?TopicLocalizedDataModel
    {
        $row = $this->related('topic_localized_data')
            ->where('language', $lang)
            ->fetch();
        return $row ? TopicLocalizedDataModel::createFromActiveRow($row) : null;
    }
}
