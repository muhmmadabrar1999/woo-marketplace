<?php

namespace Woo\Marketplace\Http\Controllers;

use Woo\Base\Http\Controllers\BaseController;
use Woo\Marketplace\Tables\VendorTable;

class VendorController extends BaseController
{
    public function index(VendorTable $table)
    {
        page_title()->setTitle(trans('plugins/marketplace::marketplace.vendors'));

        return $table->renderTable();
    }
}
