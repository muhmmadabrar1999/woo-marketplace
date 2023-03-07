<?php

namespace Woo\Location\Http\Controllers;

use Assets;
use BaseHelper;
use Woo\Base\Http\Controllers\BaseController;
use Woo\Location\Exports\CsvLocationExport;
use Woo\Location\Repositories\Interfaces\CityInterface;
use Woo\Location\Repositories\Interfaces\CountryInterface;
use Woo\Location\Repositories\Interfaces\StateInterface;
use Maatwebsite\Excel\Excel;

class ExportController extends BaseController
{
    public function index(
        CountryInterface $countryRepository,
        StateInterface $stateRepository,
        CityInterface $cityRepository
    ) {
        page_title()->setTitle(trans('plugins/location::location.export_location'));

        Assets::addScriptsDirectly(['vendor/core/plugins/location/js/export.js']);

        $countryCount = $countryRepository->count();
        $stateCount = $stateRepository->count();
        $cityCount = $cityRepository->count();

        return view('plugins/location::export.index', compact('countryCount', 'stateCount', 'cityCount'));
    }

    public function export()
    {
        BaseHelper::maximumExecutionTimeAndMemoryLimit();

        return (new CsvLocationExport())->download('exported_location.csv', Excel::CSV, ['Content-Type' => 'text/csv']);
    }
}
