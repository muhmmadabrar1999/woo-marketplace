<?php

namespace Woo\Ecommerce\Providers;

use ApiHelper;
use Woo\Base\Traits\LoadAndPublishDataTrait;
use Woo\Ecommerce\Commands\SendAbandonedCartsEmailCommand;
use Woo\Ecommerce\Facades\CartFacade;
use Woo\Ecommerce\Facades\CurrencyFacade;
use Woo\Ecommerce\Facades\EcommerceHelperFacade;
use Woo\Ecommerce\Facades\InvoiceHelperFacade;
use Woo\Ecommerce\Facades\OrderHelperFacade;
use Woo\Ecommerce\Facades\OrderReturnHelperFacade;
use Woo\Ecommerce\Facades\ProductCategoryHelperFacade;
use Woo\Ecommerce\Http\Middleware\CaptureFootprintsMiddleware;
use Woo\Ecommerce\Http\Middleware\RedirectIfCustomer;
use Woo\Ecommerce\Http\Middleware\RedirectIfNotCustomer;
use Woo\Ecommerce\Models\Address;
use Woo\Ecommerce\Models\Brand;
use Woo\Ecommerce\Models\Currency;
use Woo\Ecommerce\Models\Customer;
use Woo\Ecommerce\Models\Discount;
use Woo\Ecommerce\Models\FlashSale;
use Woo\Ecommerce\Models\GlobalOption;
use Woo\Ecommerce\Models\GlobalOptionValue;
use Woo\Ecommerce\Models\GroupedProduct;
use Woo\Ecommerce\Models\Invoice;
use Woo\Ecommerce\Models\Option;
use Woo\Ecommerce\Models\OptionValue;
use Woo\Ecommerce\Models\Order;
use Woo\Ecommerce\Models\OrderAddress;
use Woo\Ecommerce\Models\OrderHistory;
use Woo\Ecommerce\Models\OrderProduct;
use Woo\Ecommerce\Models\OrderReturn;
use Woo\Ecommerce\Models\OrderReturnItem;
use Woo\Ecommerce\Models\Product;
use Woo\Ecommerce\Models\ProductAttribute;
use Woo\Ecommerce\Models\ProductAttributeSet;
use Woo\Ecommerce\Models\ProductCategory;
use Woo\Ecommerce\Models\ProductCollection;
use Woo\Ecommerce\Models\ProductLabel;
use Woo\Ecommerce\Models\ProductTag;
use Woo\Ecommerce\Models\ProductVariation;
use Woo\Ecommerce\Models\ProductVariationItem;
use Woo\Ecommerce\Models\Review;
use Woo\Ecommerce\Models\Shipment;
use Woo\Ecommerce\Models\ShipmentHistory;
use Woo\Ecommerce\Models\Shipping;
use Woo\Ecommerce\Models\ShippingRule;
use Woo\Ecommerce\Models\ShippingRuleItem;
use Woo\Ecommerce\Models\StoreLocator;
use Woo\Ecommerce\Models\Tax;
use Woo\Ecommerce\Models\Wishlist;
use Woo\Ecommerce\Repositories\Caches\AddressCacheDecorator;
use Woo\Ecommerce\Repositories\Caches\BrandCacheDecorator;
use Woo\Ecommerce\Repositories\Caches\CurrencyCacheDecorator;
use Woo\Ecommerce\Repositories\Caches\CustomerCacheDecorator;
use Woo\Ecommerce\Repositories\Caches\DiscountCacheDecorator;
use Woo\Ecommerce\Repositories\Caches\FlashSaleCacheDecorator;
use Woo\Ecommerce\Repositories\Caches\GlobalOptionCacheDecorator;
use Woo\Ecommerce\Repositories\Caches\GroupedProductCacheDecorator;
use Woo\Ecommerce\Repositories\Caches\InvoiceCacheDecorator;
use Woo\Ecommerce\Repositories\Caches\OrderAddressCacheDecorator;
use Woo\Ecommerce\Repositories\Caches\OrderCacheDecorator;
use Woo\Ecommerce\Repositories\Caches\OrderHistoryCacheDecorator;
use Woo\Ecommerce\Repositories\Caches\OrderProductCacheDecorator;
use Woo\Ecommerce\Repositories\Caches\OrderReturnCacheDecorator;
use Woo\Ecommerce\Repositories\Caches\OrderReturnItemCacheDecorator;
use Woo\Ecommerce\Repositories\Caches\ProductAttributeCacheDecorator;
use Woo\Ecommerce\Repositories\Caches\ProductAttributeSetCacheDecorator;
use Woo\Ecommerce\Repositories\Caches\ProductCacheDecorator;
use Woo\Ecommerce\Repositories\Caches\ProductCategoryCacheDecorator;
use Woo\Ecommerce\Repositories\Caches\ProductCollectionCacheDecorator;
use Woo\Ecommerce\Repositories\Caches\ProductLabelCacheDecorator;
use Woo\Ecommerce\Repositories\Caches\ProductTagCacheDecorator;
use Woo\Ecommerce\Repositories\Caches\ProductVariationCacheDecorator;
use Woo\Ecommerce\Repositories\Caches\ProductVariationItemCacheDecorator;
use Woo\Ecommerce\Repositories\Caches\ReviewCacheDecorator;
use Woo\Ecommerce\Repositories\Caches\ShipmentCacheDecorator;
use Woo\Ecommerce\Repositories\Caches\ShipmentHistoryCacheDecorator;
use Woo\Ecommerce\Repositories\Caches\ShippingCacheDecorator;
use Woo\Ecommerce\Repositories\Caches\ShippingRuleCacheDecorator;
use Woo\Ecommerce\Repositories\Caches\ShippingRuleItemCacheDecorator;
use Woo\Ecommerce\Repositories\Caches\StoreLocatorCacheDecorator;
use Woo\Ecommerce\Repositories\Caches\TaxCacheDecorator;
use Woo\Ecommerce\Repositories\Caches\WishlistCacheDecorator;
use Woo\Ecommerce\Repositories\Eloquent\AddressRepository;
use Woo\Ecommerce\Repositories\Eloquent\BrandRepository;
use Woo\Ecommerce\Repositories\Eloquent\CurrencyRepository;
use Woo\Ecommerce\Repositories\Eloquent\CustomerRepository;
use Woo\Ecommerce\Repositories\Eloquent\DiscountRepository;
use Woo\Ecommerce\Repositories\Eloquent\FlashSaleRepository;
use Woo\Ecommerce\Repositories\Eloquent\GlobalOptionRepository;
use Woo\Ecommerce\Repositories\Eloquent\GroupedProductRepository;
use Woo\Ecommerce\Repositories\Eloquent\InvoiceRepository;
use Woo\Ecommerce\Repositories\Eloquent\OrderAddressRepository;
use Woo\Ecommerce\Repositories\Eloquent\OrderHistoryRepository;
use Woo\Ecommerce\Repositories\Eloquent\OrderProductRepository;
use Woo\Ecommerce\Repositories\Eloquent\OrderRepository;
use Woo\Ecommerce\Repositories\Eloquent\OrderReturnItemRepository;
use Woo\Ecommerce\Repositories\Eloquent\OrderReturnRepository;
use Woo\Ecommerce\Repositories\Eloquent\ProductAttributeRepository;
use Woo\Ecommerce\Repositories\Eloquent\ProductAttributeSetRepository;
use Woo\Ecommerce\Repositories\Eloquent\ProductCategoryRepository;
use Woo\Ecommerce\Repositories\Eloquent\ProductCollectionRepository;
use Woo\Ecommerce\Repositories\Eloquent\ProductLabelRepository;
use Woo\Ecommerce\Repositories\Eloquent\ProductRepository;
use Woo\Ecommerce\Repositories\Eloquent\ProductTagRepository;
use Woo\Ecommerce\Repositories\Eloquent\ProductVariationItemRepository;
use Woo\Ecommerce\Repositories\Eloquent\ProductVariationRepository;
use Woo\Ecommerce\Repositories\Eloquent\ReviewRepository;
use Woo\Ecommerce\Repositories\Eloquent\ShipmentHistoryRepository;
use Woo\Ecommerce\Repositories\Eloquent\ShipmentRepository;
use Woo\Ecommerce\Repositories\Eloquent\ShippingRepository;
use Woo\Ecommerce\Repositories\Eloquent\ShippingRuleItemRepository;
use Woo\Ecommerce\Repositories\Eloquent\ShippingRuleRepository;
use Woo\Ecommerce\Repositories\Eloquent\StoreLocatorRepository;
use Woo\Ecommerce\Repositories\Eloquent\TaxRepository;
use Woo\Ecommerce\Repositories\Eloquent\WishlistRepository;
use Woo\Ecommerce\Repositories\Interfaces\AddressInterface;
use Woo\Ecommerce\Repositories\Interfaces\BrandInterface;
use Woo\Ecommerce\Repositories\Interfaces\CurrencyInterface;
use Woo\Ecommerce\Repositories\Interfaces\CustomerInterface;
use Woo\Ecommerce\Repositories\Interfaces\DiscountInterface;
use Woo\Ecommerce\Repositories\Interfaces\FlashSaleInterface;
use Woo\Ecommerce\Repositories\Interfaces\GlobalOptionInterface;
use Woo\Ecommerce\Repositories\Interfaces\GroupedProductInterface;
use Woo\Ecommerce\Repositories\Interfaces\InvoiceInterface;
use Woo\Ecommerce\Repositories\Interfaces\OrderAddressInterface;
use Woo\Ecommerce\Repositories\Interfaces\OrderHistoryInterface;
use Woo\Ecommerce\Repositories\Interfaces\OrderInterface;
use Woo\Ecommerce\Repositories\Interfaces\OrderProductInterface;
use Woo\Ecommerce\Repositories\Interfaces\OrderReturnInterface;
use Woo\Ecommerce\Repositories\Interfaces\OrderReturnItemInterface;
use Woo\Ecommerce\Repositories\Interfaces\ProductAttributeInterface;
use Woo\Ecommerce\Repositories\Interfaces\ProductAttributeSetInterface;
use Woo\Ecommerce\Repositories\Interfaces\ProductCategoryInterface;
use Woo\Ecommerce\Repositories\Interfaces\ProductCollectionInterface;
use Woo\Ecommerce\Repositories\Interfaces\ProductInterface;
use Woo\Ecommerce\Repositories\Interfaces\ProductLabelInterface;
use Woo\Ecommerce\Repositories\Interfaces\ProductTagInterface;
use Woo\Ecommerce\Repositories\Interfaces\ProductVariationInterface;
use Woo\Ecommerce\Repositories\Interfaces\ProductVariationItemInterface;
use Woo\Ecommerce\Repositories\Interfaces\ReviewInterface;
use Woo\Ecommerce\Repositories\Interfaces\ShipmentHistoryInterface;
use Woo\Ecommerce\Repositories\Interfaces\ShipmentInterface;
use Woo\Ecommerce\Repositories\Interfaces\ShippingInterface;
use Woo\Ecommerce\Repositories\Interfaces\ShippingRuleInterface;
use Woo\Ecommerce\Repositories\Interfaces\ShippingRuleItemInterface;
use Woo\Ecommerce\Repositories\Interfaces\StoreLocatorInterface;
use Woo\Ecommerce\Repositories\Interfaces\TaxInterface;
use Woo\Ecommerce\Repositories\Interfaces\WishlistInterface;
use Woo\Ecommerce\Services\Footprints\Footprinter;
use Woo\Ecommerce\Services\Footprints\FootprinterInterface;
use Woo\Ecommerce\Services\Footprints\TrackingFilter;
use Woo\Ecommerce\Services\Footprints\TrackingFilterInterface;
use Woo\Ecommerce\Services\Footprints\TrackingLogger;
use Woo\Ecommerce\Services\Footprints\TrackingLoggerInterface;
use Woo\Ecommerce\Services\HandleApplyCouponService;
use Woo\Ecommerce\Services\HandleRemoveCouponService;
use Woo\LanguageAdvanced\Supports\LanguageAdvancedManager;
use Woo\Payment\Models\Payment;
use Cart;
use EcommerceHelper;
use EmailHandler;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Http\Request;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use SeoHelper;
use SlugHelper;
use SocialService;

class EcommerceServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        config([
            'auth.guards.customer' => [
                'driver' => 'session',
                'provider' => 'customers',
            ],
            'auth.providers.customers' => [
                'driver' => 'eloquent',
                'model' => Customer::class,
            ],
            'auth.passwords.customers' => [
                'provider' => 'customers',
                'table' => 'ec_customer_password_resets',
                'expire' => 60,
            ],
        ]);

        /**
         * @var Router $router
         */
        $router = $this->app['router'];

        $router->aliasMiddleware('customer', RedirectIfNotCustomer::class);
        $router->aliasMiddleware('customer.guest', RedirectIfCustomer::class);
        $router->pushMiddlewareToGroup('web', CaptureFootprintsMiddleware::class);

        $this->app->bind(ProductInterface::class, function () {
            return new ProductCacheDecorator(
                new ProductRepository(new Product())
            );
        });

        $this->app->bind(ProductCategoryInterface::class, function () {
            return new ProductCategoryCacheDecorator(
                new ProductCategoryRepository(new ProductCategory())
            );
        });

        $this->app->bind(ProductTagInterface::class, function () {
            return new ProductTagCacheDecorator(
                new ProductTagRepository(new ProductTag())
            );
        });

        $this->app->bind(GlobalOptionInterface::class, function () {
            return new GlobalOptionCacheDecorator(
                new GlobalOptionRepository(new GlobalOption())
            );
        });

        $this->app->bind(BrandInterface::class, function () {
            return new BrandCacheDecorator(
                new BrandRepository(new Brand())
            );
        });

        $this->app->bind(ProductCollectionInterface::class, function () {
            return new ProductCollectionCacheDecorator(
                new ProductCollectionRepository(new ProductCollection())
            );
        });

        $this->app->bind(CurrencyInterface::class, function () {
            return new CurrencyCacheDecorator(
                new CurrencyRepository(new Currency())
            );
        });

        $this->app->bind(ProductAttributeSetInterface::class, function () {
            return new ProductAttributeSetCacheDecorator(
                new ProductAttributeSetRepository(new ProductAttributeSet()),
                ECOMMERCE_GROUP_CACHE_KEY
            );
        });

        $this->app->bind(ProductAttributeInterface::class, function () {
            return new ProductAttributeCacheDecorator(
                new ProductAttributeRepository(new ProductAttribute()),
                ECOMMERCE_GROUP_CACHE_KEY
            );
        });

        $this->app->bind(ProductVariationInterface::class, function () {
            return new ProductVariationCacheDecorator(
                new ProductVariationRepository(new ProductVariation()),
                ECOMMERCE_GROUP_CACHE_KEY
            );
        });

        $this->app->bind(ProductVariationItemInterface::class, function () {
            return new ProductVariationItemCacheDecorator(
                new ProductVariationItemRepository(new ProductVariationItem()),
                ECOMMERCE_GROUP_CACHE_KEY
            );
        });

        $this->app->bind(TaxInterface::class, function () {
            return new TaxCacheDecorator(
                new TaxRepository(new Tax())
            );
        });

        $this->app->bind(ReviewInterface::class, function () {
            return new ReviewCacheDecorator(
                new ReviewRepository(new Review())
            );
        });

        $this->app->bind(ShippingInterface::class, function () {
            return new ShippingCacheDecorator(
                new ShippingRepository(new Shipping())
            );
        });

        $this->app->bind(ShippingRuleInterface::class, function () {
            return new ShippingRuleCacheDecorator(
                new ShippingRuleRepository(new ShippingRule())
            );
        });

        $this->app->bind(ShippingRuleItemInterface::class, function () {
            return new ShippingRuleItemCacheDecorator(
                new ShippingRuleItemRepository(new ShippingRuleItem())
            );
        });

        $this->app->bind(ShipmentInterface::class, function () {
            return new ShipmentCacheDecorator(
                new ShipmentRepository(new Shipment())
            );
        });

        $this->app->bind(ShipmentHistoryInterface::class, function () {
            return new ShipmentHistoryCacheDecorator(
                new ShipmentHistoryRepository(new ShipmentHistory())
            );
        });

        $this->app->bind(OrderInterface::class, function () {
            return new OrderCacheDecorator(
                new OrderRepository(new Order())
            );
        });

        $this->app->bind(OrderHistoryInterface::class, function () {
            return new OrderHistoryCacheDecorator(
                new OrderHistoryRepository(new OrderHistory())
            );
        });

        $this->app->bind(OrderProductInterface::class, function () {
            return new OrderProductCacheDecorator(
                new OrderProductRepository(new OrderProduct())
            );
        });

        $this->app->bind(OrderAddressInterface::class, function () {
            return new OrderAddressCacheDecorator(
                new OrderAddressRepository(new OrderAddress())
            );
        });

        $this->app->bind(OrderReturnInterface::class, function () {
            return new OrderReturnCacheDecorator(
                new OrderReturnRepository(new OrderReturn())
            );
        });

        $this->app->bind(OrderReturnItemInterface::class, function () {
            return new OrderReturnItemCacheDecorator(
                new OrderReturnItemRepository(new OrderReturnItem())
            );
        });

        $this->app->bind(DiscountInterface::class, function () {
            return new DiscountCacheDecorator(
                new DiscountRepository(new Discount())
            );
        });

        $this->app->bind(WishlistInterface::class, function () {
            return new WishlistCacheDecorator(
                new WishlistRepository(new Wishlist())
            );
        });

        $this->app->bind(AddressInterface::class, function () {
            return new AddressCacheDecorator(
                new AddressRepository(new Address())
            );
        });
        $this->app->bind(CustomerInterface::class, function () {
            return new CustomerCacheDecorator(
                new CustomerRepository(new Customer())
            );
        });

        $this->app->bind(GroupedProductInterface::class, function () {
            return new GroupedProductCacheDecorator(
                new GroupedProductRepository(new GroupedProduct())
            );
        });

        $this->app->bind(StoreLocatorInterface::class, function () {
            return new StoreLocatorCacheDecorator(
                new StoreLocatorRepository(new StoreLocator())
            );
        });

        $this->app->bind(FlashSaleInterface::class, function () {
            return new FlashSaleCacheDecorator(
                new FlashSaleRepository(new FlashSale())
            );
        });

        $this->app->bind(ProductLabelInterface::class, function () {
            return new ProductLabelCacheDecorator(
                new ProductLabelRepository(new ProductLabel())
            );
        });

        $this->app->bind(InvoiceInterface::class, function () {
            return new InvoiceCacheDecorator(new InvoiceRepository(new Invoice()));
        });

        $this->app->bind(TrackingFilterInterface::class, function ($app) {
            return $app->make(TrackingFilter::class);
        });

        $this->app->bind(TrackingLoggerInterface::class, function ($app) {
            return $app->make(TrackingLogger::class);
        });

        $this->app->singleton(FootprinterInterface::class, function ($app) {
            return $app->make(Footprinter::class);
        });

        Request::macro('footprint', function () {
            return app(FootprinterInterface::class)->footprint($this);
        });

        $this->setNamespace('plugins/ecommerce')->loadHelpers();

        $loader = AliasLoader::getInstance();
        $loader->alias('Cart', CartFacade::class);
        $loader->alias('OrderHelper', OrderHelperFacade::class);
        $loader->alias('OrderReturnHelper', OrderReturnHelperFacade::class);
        $loader->alias('EcommerceHelper', EcommerceHelperFacade::class);
        $loader->alias('ProductCategoryHelper', ProductCategoryHelperFacade::class);
        $loader->alias('CurrencyHelper', CurrencyFacade::class);
        $loader->alias('InvoiceHelper', InvoiceHelperFacade::class);
    }

    public function boot(): void
    {
        SlugHelper::registerModule(Product::class, 'Products');
        SlugHelper::registerModule(Brand::class, 'Brands');
        SlugHelper::registerModule(ProductCategory::class, 'Product Categories');
        SlugHelper::registerModule(ProductTag::class, 'Product Tags');
        SlugHelper::setPrefix(Product::class, 'products');
        SlugHelper::setPrefix(Brand::class, 'brands');
        SlugHelper::setPrefix(ProductTag::class, 'product-tags');
        SlugHelper::setPrefix(ProductCategory::class, 'product-categories');

        $this
            ->loadAndPublishConfigurations(['permissions'])
            ->loadAndPublishTranslations()
            ->loadRoutes([
                'base',
                'product',
                'tax',
                'review',
                'shipping',
                'order',
                'discount',
                'customer',
                'cart',
                'shipment',
                'wishlist',
                'compare',
                'invoice',
                'invoice-template',
            ])
            ->loadAndPublishConfigurations([
                'general',
                'shipping',
                'order',
                'cart',
                'email',
            ])
            ->loadAndPublishViews()
            ->loadMigrations()
            ->publishAssets();

        if (class_exists('ApiHelper') && ApiHelper::enabled()) {
            ApiHelper::setConfig([
                'model' => Customer::class,
                'guard' => 'customer',
                'password_broker' => 'customers',
                'verify_email' => true,
            ]);
        }

        if (File::exists(storage_path('app/invoices/template.blade.php'))) {
            $this->loadViewsFrom(storage_path('app/invoices'), 'plugins/ecommerce/invoice');
        }

        if (defined('LANGUAGE_MODULE_SCREEN_NAME') && defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME')) {
            LanguageAdvancedManager::registerModule(Product::class, [
                'name',
                'description',
                'content',
            ]);

            if (config('plugins.ecommerce.general.enable_faq_in_product_details', false)) {
                LanguageAdvancedManager::addTranslatableMetaBox('faq_schema_config_wrapper');

                LanguageAdvancedManager::registerModule(Product::class, array_merge(
                    LanguageAdvancedManager::getTranslatableColumns(Product::class),
                    ['faq_schema_config']
                ));
            }

            LanguageAdvancedManager::registerModule(ProductCategory::class, [
                'name',
                'description',
            ]);

            LanguageAdvancedManager::registerModule(ProductAttribute::class, [
                'title',
            ]);

            LanguageAdvancedManager::addTranslatableMetaBox('attributes_list');

            LanguageAdvancedManager::registerModule(ProductAttribute::class, array_merge(
                LanguageAdvancedManager::getTranslatableColumns(ProductAttribute::class),
                ['attributes']
            ));

            LanguageAdvancedManager::registerModule(ProductAttributeSet::class, [
                'title',
            ]);

            LanguageAdvancedManager::registerModule(Brand::class, [
                'name',
                'description',
            ]);

            LanguageAdvancedManager::registerModule(ProductCollection::class, [
                'name',
                'description',
            ]);

            LanguageAdvancedManager::registerModule(ProductLabel::class, [
                'name',
                'description',
            ]);

            LanguageAdvancedManager::registerModule(FlashSale::class, [
                'name',
                'description',
            ]);

            LanguageAdvancedManager::registerModule(ProductTag::class, [
                'name',
            ]);

            LanguageAdvancedManager::registerModule(GlobalOption::class, [
                'name',
            ]);

            LanguageAdvancedManager::registerModule(Option::class, [
                'name',
            ]);

            LanguageAdvancedManager::registerModule(GlobalOptionValue::class, [
                'option_value',
            ]);

            LanguageAdvancedManager::registerModule(OptionValue::class, [
                'option_value',
            ]);

            LanguageAdvancedManager::addTranslatableMetaBox('product_options_box');

            add_action(LANGUAGE_ADVANCED_ACTION_SAVED, function ($data, $request) {
                switch (get_class($data)) {
                    case Product::class:
                        $variations = $data->variations()->get();

                        foreach ($variations as $variation) {
                            if (! $variation->product->id) {
                                continue;
                            }

                            LanguageAdvancedManager::save($variation->product, $request);
                        }

                        $options = $request->input('options', []) ?: [];

                        if (! $options) {
                            return;
                        }

                        $newRequest = new Request();

                        $newRequest->replace([
                            'language' => $request->input('language'),
                            'ref_lang' => $request->input('ref_lang'),
                        ]);

                        foreach ($options as $item) {
                            $option = Option::find($item['id']);

                            $newRequest->merge(['name' => $item['name']]);

                            if ($option) {
                                LanguageAdvancedManager::save($option, $newRequest);
                            }

                            $newRequest = new Request();

                            $newRequest->replace([
                                'language' => $request->input('language'),
                                'ref_lang' => $request->input('ref_lang'),
                            ]);

                            foreach ($item['values'] as $value) {
                                if (! $value['id']) {
                                    continue;
                                }

                                $optionValue = OptionValue::find($value['id']);

                                $newRequest->merge([
                                    'option_value' => $value['option_value'],
                                ]);

                                if ($optionValue) {
                                    LanguageAdvancedManager::save($optionValue, $newRequest);
                                }
                            }
                        }

                        break;
                    case ProductAttributeSet::class:

                        $attributes = json_decode($request->input('attributes', '[]'), true) ?: [];

                        if (! $attributes) {
                            break;
                        }

                        $request = new Request();
                        $request->replace([
                            'language' => request()->input('language'),
                            'ref_lang' => request()->input('ref_lang'),
                        ]);

                        foreach ($attributes as $item) {
                            $request->merge([
                                'title' => $item['title'],
                            ]);

                            $attribute = $this->app->make(ProductAttributeInterface::class)->findById($item['id']);

                            if ($attribute) {
                                LanguageAdvancedManager::save($attribute, $request);
                            }
                        }

                        break;
                    case GlobalOption::class:

                        $option = GlobalOption::find($request->input('id'));

                        if ($option) {
                            LanguageAdvancedManager::save($option, $request);
                        }

                        $options = $request->input('options', []) ?: [];

                        if (! $options) {
                            return;
                        }

                        $newRequest = new Request();

                        $newRequest->replace([
                            'language' => $request->input('language'),
                            'ref_lang' => $request->input('ref_lang'),
                        ]);

                        foreach ($options as $value) {
                            if (! $value['id']) {
                                continue;
                            }

                            $optionValue = GlobalOptionValue::find($value['id']);

                            $newRequest->merge([
                                'option_value' => $value['option_value'],
                            ]);

                            if ($optionValue) {
                                LanguageAdvancedManager::save($optionValue, $newRequest);
                            }
                        }

                        break;
                }
            }, 1234, 2);
        }

        EmailHandler::addTemplateSettings(ECOMMERCE_MODULE_SCREEN_NAME, config('plugins.ecommerce.email', []));

        $this->app->register(HookServiceProvider::class);

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                    'id' => 'cms-plugins-ecommerce',
                    'priority' => 8,
                    'parent_id' => null,
                    'name' => 'plugins/ecommerce::ecommerce.name',
                    'icon' => 'fa fa-shopping-cart',
                    'url' => route('products.index'),
                    'permissions' => ['plugins.ecommerce'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-ecommerce-report',
                    'priority' => 0,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name' => 'plugins/ecommerce::reports.name',
                    'icon' => 'far fa-chart-bar',
                    'url' => route('ecommerce.report.index'),
                    'permissions' => ['ecommerce.report.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-flash-sale',
                    'priority' => 0,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name' => 'plugins/ecommerce::flash-sale.name',
                    'icon' => 'fa fa-bolt',
                    'url' => route('flash-sale.index'),
                    'permissions' => ['flash-sale.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-ecommerce-order',
                    'priority' => 1,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name' => 'plugins/ecommerce::order.menu',
                    'icon' => 'fa fa-shopping-bag',
                    'url' => route('orders.index'),
                    'permissions' => ['orders.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-ecommerce-invoice',
                    'priority' => 2,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name' => 'plugins/ecommerce::invoice.name',
                    'icon' => 'fas fa-book',
                    'url' => route('ecommerce.invoice.index'),
                    'permissions' => ['ecommerce.invoice.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-invoice-template',
                    'priority' => 2,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name' => 'plugins/ecommerce::invoice-template.name',
                    'icon' => 'fas fa-book',
                    'url' => route('invoice-template.index'),
                    'permissions' => ['ecommerce.invoice-template.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-ecommerce-incomplete-order',
                    'priority' => 2,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name' => 'plugins/ecommerce::order.incomplete_order',
                    'icon' => 'fas fa-shopping-basket',
                    'url' => route('orders.incomplete-list'),
                    'permissions' => ['orders.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-ecommerce-order-return',
                    'priority' => 3,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name' => 'plugins/ecommerce::order.order_return',
                    'icon' => 'fa fa-cart-arrow-down',
                    'url' => route('order_returns.index'),
                    'permissions' => ['orders.edit'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-ecommerce.product',
                    'priority' => 3,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name' => 'plugins/ecommerce::products.name',
                    'icon' => 'fa fa-camera',
                    'url' => route('products.index'),
                    'permissions' => ['products.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-product-categories',
                    'priority' => 4,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name' => 'plugins/ecommerce::product-categories.name',
                    'icon' => 'fa fa-archive',
                    'url' => route('product-categories.index'),
                    'permissions' => ['product-categories.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-product-tag',
                    'priority' => 4,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name' => 'plugins/ecommerce::product-tag.name',
                    'icon' => 'fa fa-tag',
                    'url' => route('product-tag.index'),
                    'permissions' => ['product-tag.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-product-attribute',
                    'priority' => 5,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name' => 'plugins/ecommerce::product-attributes.name',
                    'icon' => 'fas fa-glass-martini',
                    'url' => route('product-attribute-sets.index'),
                    'permissions' => ['product-attribute-sets.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-ecommerce-global-options',
                    'priority' => 3,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name' => 'plugins/ecommerce::product-option.name',
                    'icon' => 'fa fa-database',
                    'url' => route('global-option.index'),
                    'permissions' => ['global-option.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-brands',
                    'priority' => 6,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name' => 'plugins/ecommerce::brands.name',
                    'icon' => 'fa fa-registered',
                    'url' => route('brands.index'),
                    'permissions' => ['brands.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-product-collections',
                    'priority' => 7,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name' => 'plugins/ecommerce::product-collections.name',
                    'icon' => 'fa fa-file-excel',
                    'url' => route('product-collections.index'),
                    'permissions' => ['product-collections.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-product-label',
                    'priority' => 8,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name' => 'plugins/ecommerce::product-label.name',
                    'icon' => 'fas fa-tags',
                    'url' => route('product-label.index'),
                    'permissions' => ['product-label.index'],
                ])
                ->registerItem([
                    'id' => 'cms-ecommerce-review',
                    'priority' => 9,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name' => 'plugins/ecommerce::review.name',
                    'icon' => 'fa fa-comments',
                    'url' => route('reviews.index'),
                    'permissions' => ['reviews.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-ecommerce-shipping-provider',
                    'priority' => 10,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name' => 'plugins/ecommerce::shipping.shipping',
                    'icon' => 'fas fa-shipping-fast',
                    'url' => route('shipping_methods.index'),
                    'permissions' => ['shipping_methods.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-ecommerce-shipping-shipments',
                    'priority' => 11,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name' => 'plugins/ecommerce::shipping.shipments',
                    'icon' => 'fas fa-people-carry',
                    'url' => route('ecommerce.shipments.index'),
                    'permissions' => ['orders.edit'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-ecommerce-discount',
                    'priority' => 12,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name' => 'plugins/ecommerce::discount.name',
                    'icon' => 'fa fa-gift',
                    'url' => route('discounts.index'),
                    'permissions' => ['discounts.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-ecommerce-customer',
                    'priority' => 13,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name' => 'plugins/ecommerce::customer.name',
                    'icon' => 'fa fa-users',
                    'url' => route('customers.index'),
                    'permissions' => ['customers.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-ecommerce.basic-settings',
                    'priority' => 998,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name' => 'plugins/ecommerce::ecommerce.basic_settings',
                    'icon' => 'fas fa-cogs',
                    'url' => route('ecommerce.settings'),
                    'permissions' => ['ecommerce.settings'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-ecommerce.advanced-settings',
                    'priority' => 999,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name' => 'plugins/ecommerce::ecommerce.advanced_settings',
                    'icon' => 'fas fa-plus',
                    'url' => route('ecommerce.advanced-settings'),
                    'permissions' => ['ecommerce.settings'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-ecommerce.tracking-settings',
                    'priority' => 999,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name' => 'plugins/ecommerce::ecommerce.setting.tracking_settings',
                    'icon' => 'fa-solid fa-chart-pie',
                    'url' => route('ecommerce.tracking-settings'),
                    'permissions' => ['ecommerce.settings'],
                ]);

            if (EcommerceHelper::isTaxEnabled()) {
                dashboard_menu()->registerItem([
                    'id' => 'cms-plugins-ecommerce-tax',
                    'priority' => 14,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name' => 'plugins/ecommerce::tax.name',
                    'icon' => 'fas fa-money-check-alt',
                    'url' => route('tax.index'),
                    'permissions' => ['tax.index'],
                ]);
            }

            if (! dashboard_menu()->hasItem('cms-core-tools')) {
                dashboard_menu()->registerItem([
                    'id' => 'cms-core-tools',
                    'priority' => 96,
                    'parent_id' => null,
                    'name' => 'core/base::base.tools',
                    'icon' => 'fas fa-tools',
                    'url' => '',
                    'permissions' => [],
                ]);
            }

            dashboard_menu()
                ->registerItem([
                    'id' => 'cms-core-tools-ecommerce-bulk-import',
                    'priority' => 1,
                    'parent_id' => 'cms-core-tools',
                    'name' => 'plugins/ecommerce::bulk-import.menu',
                    'icon' => 'fas fa-file-import',
                    'url' => route('ecommerce.bulk-import.index'),
                    'permissions' => ['ecommerce.bulk-import.index'],
                ])
                ->registerItem([
                    'id' => 'cms-core-tools-ecommerce-export-products',
                    'priority' => 2,
                    'parent_id' => 'cms-core-tools',
                    'name' => 'plugins/ecommerce::export.products.name',
                    'icon' => 'fas fa-file-export',
                    'url' => route('ecommerce.export.products.index'),
                    'permissions' => ['ecommerce.export.products.index'],
                ]);
        });

        $this->app->booted(function () {
            SeoHelper::registerModule([
                Product::class,
                Brand::class,
                ProductCategory::class,
                ProductTag::class,
            ]);

            $this->app->make(Schedule::class)->command(SendAbandonedCartsEmailCommand::class)->weekly('23:30');

            if (is_plugin_active('payment')) {
                Payment::resolveRelationUsing('order', function ($model) {
                    return $model->belongsTo(Order::class, 'order_id')->withDefault();
                });
            }

            if (defined('SOCIAL_LOGIN_MODULE_SCREEN_NAME') && Route::has('customer.login')) {
                SocialService::registerModule([
                    'guard' => 'customer',
                    'model' => Customer::class,
                    'login_url' => route('customer.login'),
                    'redirect_url' => route('public.index'),
                ]);
            }
        });

        $this->app->register(EventServiceProvider::class);
        $this->app->register(CommandServiceProvider::class);

        Event::listen(['cart.removed', 'cart.stored', 'cart.restored', 'cart.updated'], function ($cart) {
            $coupon = session('applied_coupon_code');
            if ($coupon) {
                $this->app->make(HandleRemoveCouponService::class)->execute();
                if (Cart::count() || ($cart instanceof \Woo\Ecommerce\Cart\Cart && $cart->count())) {
                    $this->app->make(HandleApplyCouponService::class)->execute($coupon);
                }
            }
        });
    }
}
