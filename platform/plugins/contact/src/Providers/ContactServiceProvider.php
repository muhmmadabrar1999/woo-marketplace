<?php

namespace Woo\Contact\Providers;

use EmailHandler;
use Illuminate\Routing\Events\RouteMatched;
use Woo\Base\Traits\LoadAndPublishDataTrait;
use Woo\Contact\Models\ContactReply;
use Woo\Contact\Repositories\Caches\ContactReplyCacheDecorator;
use Woo\Contact\Repositories\Eloquent\ContactReplyRepository;
use Woo\Contact\Repositories\Interfaces\ContactInterface;
use Woo\Contact\Models\Contact;
use Woo\Contact\Repositories\Caches\ContactCacheDecorator;
use Woo\Contact\Repositories\Eloquent\ContactRepository;
use Woo\Contact\Repositories\Interfaces\ContactReplyInterface;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class ContactServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind(ContactInterface::class, function () {
            return new ContactCacheDecorator(new ContactRepository(new Contact()));
        });

        $this->app->bind(ContactReplyInterface::class, function () {
            return new ContactReplyCacheDecorator(new ContactReplyRepository(new ContactReply()));
        });
    }

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/contact')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['permissions', 'email'])
            ->loadRoutes()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadMigrations()
            ->publishAssets();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id' => 'cms-plugins-contact',
                'priority' => 120,
                'parent_id' => null,
                'name' => 'plugins/contact::contact.menu',
                'icon' => 'far fa-envelope',
                'url' => route('contacts.index'),
                'permissions' => ['contacts.index'],
            ]);

            EmailHandler::addTemplateSettings(CONTACT_MODULE_SCREEN_NAME, config('plugins.contact.email', []));
        });

        $this->app->booted(function () {
            $this->app->register(HookServiceProvider::class);
        });
    }
}
