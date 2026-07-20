<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Shop\App\Services\Saby\SabyIntegration;

class RunUpdateCatalog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'saby:run-update-catalog';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Загрузка каталога из Saby';

    /**
     * Execute the console command.
     */
    public function handle(SabyIntegration $saby): bool
    {
        $saby->getCatalog();

        $this->info('');

        return Command::SUCCESS;
    }
}
