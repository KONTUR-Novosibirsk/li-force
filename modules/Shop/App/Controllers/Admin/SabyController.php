<?php

namespace Modules\Shop\App\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class SabyController extends Controller
{
    public function updateCatalog(Request $request)
    {
        try {
            Artisan::call('saby:run-update-catalog');

            return response()->json([
                'success' => true,
                'message' => 'Каталог успешно обновлен!',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка: '.$e->getMessage(),
            ], 500);
        }
    }

    public function getProgress()
    {
        return response()->json(
            Cache::get('catalog_update_progress', [
                'status' => 'idle',
                'stage' => '',
                'progress' => 0,
                'current' => 0,
                'total' => 0
            ])
        );
    }
}
