<?php

namespace Woo\Ecommerce\Providers;

use Woo\Base\Events\CreatedContentEvent;
use Woo\Base\Events\RenderingAdminWidgetEvent;
use Woo\Base\Events\UpdatedContentEvent;
use Woo\Ecommerce\Events\OrderCancelledEvent;
use Woo\Ecommerce\Events\OrderCompletedEvent;
use Woo\Ecommerce\Events\OrderCreated;
use Woo\Ecommerce\Events\OrderPaymentConfirmedEvent;
use Woo\Ecommerce\Events\OrderPlacedEvent;
use Woo\Ecommerce\Events\OrderReturnedEvent;
use Woo\Ecommerce\Events\ProductQuantityUpdatedEvent;
use Woo\Ecommerce\Events\ProductViewed;
use Woo\Ecommerce\Events\ShippingStatusChanged;
use Woo\Ecommerce\Listeners\AddLanguageForVariantsListener;
use Woo\Ecommerce\Listeners\GenerateInvoiceListener;
use Woo\Ecommerce\Listeners\OrderCancelledNotification;
use Woo\Ecommerce\Listeners\OrderCreatedNotification;
use Woo\Ecommerce\Listeners\OrderPaymentConfirmedNotification;
use Woo\Ecommerce\Listeners\OrderReturnedNotification;
use Woo\Ecommerce\Listeners\RegisterEcommerceWidget;
use Woo\Ecommerce\Listeners\RenderingSiteMapListener;
use Woo\Ecommerce\Listeners\SendMailsAfterCustomerRegistered;
use Woo\Ecommerce\Listeners\SendProductReviewsMailAfterOrderCompleted;
use Woo\Ecommerce\Listeners\SendShippingStatusChangedNotification;
use Woo\Ecommerce\Listeners\SendWebhookWhenOrderPlaced;
use Woo\Ecommerce\Listeners\UpdateProductStockStatus;
use Woo\Ecommerce\Listeners\UpdateProductView;
use Woo\Theme\Events\RenderingSiteMapEvent;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        RenderingSiteMapEvent::class => [
            RenderingSiteMapListener::class,
        ],
        CreatedContentEvent::class => [
            AddLanguageForVariantsListener::class,
        ],
        UpdatedContentEvent::class => [
            AddLanguageForVariantsListener::class,
        ],
        Registered::class => [
            SendMailsAfterCustomerRegistered::class,
        ],
        OrderPlacedEvent::class => [
            SendWebhookWhenOrderPlaced::class,
            GenerateInvoiceListener::class,
            OrderCreatedNotification::class,
        ],
        OrderCreated::class => [
            GenerateInvoiceListener::class,
            OrderCreatedNotification::class,
        ],
        ProductQuantityUpdatedEvent::class => [
            UpdateProductStockStatus::class,
        ],
        OrderCompletedEvent::class => [
            SendProductReviewsMailAfterOrderCompleted::class,
        ],
        ProductViewed::class => [
            UpdateProductView::class,
        ],
        ShippingStatusChanged::class => [
            SendShippingStatusChangedNotification::class,
        ],
        RenderingAdminWidgetEvent::class => [
            RegisterEcommerceWidget::class,
        ],
        OrderPaymentConfirmedEvent::class => [
            OrderPaymentConfirmedNotification::class,
        ],
        OrderCancelledEvent::class => [
            OrderCancelledNotification::class,
        ],
        OrderReturnedEvent::class => [
            OrderReturnedNotification::class,
        ],
    ];
}
