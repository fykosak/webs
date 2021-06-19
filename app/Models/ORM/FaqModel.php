<?php

namespace App\Models\ORM;

use Fykosak\NetteORM\AbstractModel;

/**
 * @property-read int faq_id
 * @property-read string question
 * @property-read string answer
 * @property-read string lang
 * @property-read string|null category
 */
class FaqModel extends AbstractModel {

    public function getCategory(): string {
        switch ($this->category) {
            case 'game' :
                return _('Game');
            default :
                return _('Other');
        }
    }
}
