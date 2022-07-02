<?php

declare(strict_types=1);

namespace App\Models\ORM\Problems;

use Fykosak\NetteORM\Model;
use Nette\Database\Table\ActiveRow;

/**
 * @property-read int id
 * @property-read int solvers
 * @property-read double avg
 * @property-read int directory_id
 * @property-read string label
 * @property-read int points
 * @property-read ActiveRow directory
 */
class ProblemModel extends Model
{
    public function getLocalizedData(string $lang): ?ProblemLocalizedDataModel
    {
        $row = $this->related('problem_localized_data')
            ->where('language', $lang)
            ->fetch();
        return $row ? ProblemLocalizedDataModel::createFromActiveRow($row) : null;
    }

    public function getTags(): array
    {
        $tags = [];
        foreach ($this->related('problem_tag') as $row) {
            $tags[] = TagModel::createFromActiveRow($row->tag);
        }
        return $tags;
    }

    public function getTopics(): array
    {
        $topics = [];
        foreach ($this->related('problem_topic') as $row) {
            $topics[] = TopicModel::createFromActiveRow($row->topic);
        }
        return $topics;
    }

    public function getDirectory(): DirectoryModel
    {
        return DirectoryModel::createFromActiveRow($this->directory);
    }
}
