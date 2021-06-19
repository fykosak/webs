<?php

namespace App\Modules\DefaultModule;

abstract class BasePresenter extends \App\Modules\Core\BasePresenter {

    protected function getNavItems(): array {
        return [];
    }
}
