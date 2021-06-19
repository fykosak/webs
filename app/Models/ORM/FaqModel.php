<?php

namespace App\Models\ORM;

class FaqModel extends \Fykosak\NetteORM\AbstractModel {

    public function getCategory(): string {
        switch ($this->category) {
            case 'game' :
                return _('Game');
            default :
                return _('Other');
        }
    }
}
