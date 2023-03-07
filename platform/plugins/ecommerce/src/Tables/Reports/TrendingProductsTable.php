<?php

namespace Woo\Ecommerce\Tables\Reports;

use Woo\Ecommerce\Facades\EcommerceHelperFacade as EcommerceHelper;
use Woo\Ecommerce\Models\Product;
use Woo\Ecommerce\Models\ProductView;
use Woo\Ecommerce\Repositories\Interfaces\ProductInterface;
use Woo\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class TrendingProductsTable extends TableAbstract
{
    protected string $type = self::TABLE_TYPE_SIMPLE;

    protected $view = 'core/table::simple-table';

    public function __construct(
        DataTables $table,
        UrlGenerator $urlGenerator,
        ProductInterface $productRepository
    ) {
        parent::__construct($table, $urlGenerator);
        $this->repository = $productRepository;
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('name', function (Product $product) {
                return Html::link($product->url, $product->name, ['target' => '_blank']);
            })
            ->editColumn('views', function (Product $product) {
                return Html::tag('i', '', ['class' => 'fa fa-eye'])->toHtml() . ' ' . number_format($product->views_count);
            });

        return $data->escapeColumns([])->make();
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        [$startDate, $endDate] = EcommerceHelper::getDateRangeInReport(request());

        $query = $this->repository->getModel()
            ->select([
                'ec_products.id',
                'ec_products.name',
            ])
            ->addSelect([
                'views_count' => ProductView::query()
                    ->selectRaw('SUM(ec_product_views.views) as views_count')
                    ->whereColumn('ec_product_views.product_id', 'ec_products.id')
                    ->whereDate('ec_product_views.date', '>=', $startDate)
                    ->whereDate('ec_product_views.date', '<=', $endDate)
                    ->groupBy('ec_product_views.product_id'),
            ])
            ->orderByDesc('views_count')
            ->having('views_count', '>', 0)
            ->limit(5);

        return $this->applyScopes($query);
    }

    public function getColumns(): array
    {
        return $this->columns();
    }

    public function columns(): array
    {
        return [
            'id' => [
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
                'class' => 'text-start no-sort',
            ],
            'name' => [
                'title' => trans('plugins/ecommerce::reports.product_name'),
                'class' => 'text-start no-sort',
            ],
            'views' => [
                'title' => trans('plugins/ecommerce::reports.views'),
                'class' => 'text-start no-sort',
            ],
        ];
    }
}
