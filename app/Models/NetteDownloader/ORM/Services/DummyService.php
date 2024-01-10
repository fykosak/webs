<?php

declare(strict_types=1);

namespace App\Models\NetteDownloader\ORM\Services;

use Fykosak\FKSDBDownloaderCore\Requests\Request;

class DummyService extends AbstractJSONService
{
    public function get(Request $request, string $model, ?string $explicitExpiration = null)
    {
        return $this->getItem($request, [], $model, true, $explicitExpiration);
    }
}
