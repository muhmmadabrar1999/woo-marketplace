<?php

namespace Woo\Ecommerce\Http\Controllers;

use Assets;
use BaseHelper;
use Woo\Base\Http\Controllers\BaseController;
use Woo\Base\Http\Responses\BaseHttpResponse;
use Woo\Ecommerce\Exports\TemplateProductExport;
use Woo\Ecommerce\Http\Requests\BulkImportRequest;
use Woo\Ecommerce\Http\Requests\ProductRequest;
use Woo\Ecommerce\Imports\ProductImport;
use Woo\Ecommerce\Imports\ValidateProductImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;

class BulkImportController extends BaseController
{
    protected ProductImport $productImport;

    protected ProductImport|ValidateProductImport $validateProductImport;

    public function __construct(ProductImport $productImport, ValidateProductImport $validateProductImport)
    {
        $this->productImport = $productImport;
        $this->validateProductImport = $validateProductImport;
    }

    public function index()
    {
        page_title()->setTitle(trans('plugins/ecommerce::bulk-import.name'));

        Assets::addScriptsDirectly(['vendor/core/plugins/ecommerce/js/bulk-import.js']);

        $template = new TemplateProductExport('xlsx');
        $headings = $template->headings();
        $data = $template->collection();
        $rules = $template->rules();

        return view('plugins/ecommerce::bulk-import.index', compact('data', 'headings', 'rules'));
    }

    public function postImport(BulkImportRequest $request, BaseHttpResponse $response)
    {
        BaseHelper::maximumExecutionTimeAndMemoryLimit();

        $file = $request->file('file');

        $this->validateProductImport
            ->setValidatorClass(new ProductRequest())
            ->import($file);

        if ($this->validateProductImport->failures()->count()) {
            $data = [
                'total_failed' => $this->validateProductImport->failures()->count(),
                'total_error' => $this->validateProductImport->errors()->count(),
                'failures' => $this->validateProductImport->failures(),
            ];

            $message = trans('plugins/ecommerce::bulk-import.import_failed_description');

            return $response
                ->setError()
                ->setData($data)
                ->setMessage($message);
        }

        $this->productImport
            ->setValidatorClass(new ProductRequest())
            ->setImportType($request->input('type'))
            ->import($file);

        $data = [
            'total_success' => $this->productImport->successes()->count(),
            'total_failed' => $this->productImport->failures()->count(),
            'total_error' => $this->productImport->errors()->count(),
            'failures' => $this->productImport->failures(),
            'successes' => $this->productImport->successes(),
        ];

        $message = trans('plugins/ecommerce::bulk-import.imported_successfully');

        $result = trans('plugins/ecommerce::bulk-import.results', [
            'success' => $data['total_success'],
            'failed' => $data['total_failed'],
        ]);

        return $response->setData($data)->setMessage($message . ' ' . $result);
    }

    public function downloadTemplate(Request $request)
    {
        $extension = $request->input('extension');
        $extension = $extension == 'csv' ? $extension : Excel::XLSX;
        $writeType = $extension == 'csv' ? Excel::CSV : Excel::XLSX;
        $contentType = $extension == 'csv' ? ['Content-Type' => 'text/csv'] : ['Content-Type' => 'text/xlsx'];
        $fileName = 'template_products_import.' . $extension;

        return (new TemplateProductExport($extension))->download($fileName, $writeType, $contentType);
    }
}
