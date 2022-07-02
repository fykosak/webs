<?php

declare(strict_types=1);

namespace App\Models\ORM\Problems;

use Fykosak\NetteORM\Model;

/**
 * @property-read int problem_id
 * @property-read int id
 * @property-read string language
 * @property-read string|null title
 * @property-read string|null origin
 * @property-read string|null task
 * @property-read string|null solution
 * @property-read string|null human_result
 */
class ProblemLocalizedDataModel extends Model
{
}
