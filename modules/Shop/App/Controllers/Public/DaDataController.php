<?php

namespace Modules\Shop\App\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Shop\App\Services\DaData\DaDataApiService;

class DaDataController extends Controller
{
    /**
     * Получает список адресов в количестве 10
     *
     * @param Request $request
     * @param DaDataApiService $daData
     *
     * @return array
     */
    public function getAddress(Request $request, DaDataApiService $daData): array
    {
        $address = $request->input('q');

        return $daData->getAddresses($address);
    }
}
