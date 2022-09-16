<?php

declare(strict_types=1);

namespace App\Models\ORM;

use Fykosak\NetteORM\Model;

/**
 * @property-read int faq_id
 * @property-read string question
 * @property-read string answer
 * @property-read string lang
 * @property-read string|null category
 */
class FaqModel extends Model
{

}
