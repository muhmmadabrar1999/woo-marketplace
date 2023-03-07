<?php

namespace Woo\JsValidation\Providers;

use Woo\Base\Traits\LoadAndPublishDataTrait;
use Woo\JsValidation\Javascript\ValidatorHandler;
use Woo\JsValidation\JsValidatorFactory;
use Woo\JsValidation\RemoteValidationMiddleware;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;

class JsValidationServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        $this->setNamespace('core/js-validation')
            ->loadAndPublishConfigurations(['js-validation'])
            ->loadAndPublishViews()
            ->publishAssets();

        $this->bootstrapValidator();

        if ($this->app['config']->get('core.js-validation.js-validation.disable_remote_validation') === false) {
            $this->app[Kernel::class]->pushMiddleware(RemoteValidationMiddleware::class);
        }
    }

    protected function bootstrapValidator(): void
    {
        $callback = function () {
            return true;
        };

        $this->app['validator']->extend(ValidatorHandler::JS_VALIDATION_DISABLE, $callback);
    }

    public function register(): void
    {
        $this->app->singleton('js-validator', function ($app) {
            $config = $app['config']->get('core.js-validation.js-validation');

            return new JsValidatorFactory($app, $config);
        });
    }
}
