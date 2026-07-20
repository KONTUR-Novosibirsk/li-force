<?php

namespace Modules\Shop\App\Services\Parse;

class LiForceDBOrchestrator
{
    public function __construct(protected LiForceDBService $db)
    {
    }

    public function run(callable $log): void
    {
        $this->db->addDB($log);
    }
}
