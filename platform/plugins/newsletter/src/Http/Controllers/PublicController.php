<?php

namespace Woo\Newsletter\Http\Controllers;

use Woo\Base\Http\Responses\BaseHttpResponse;
use Woo\Newsletter\Enums\NewsletterStatusEnum;
use Woo\Newsletter\Events\SubscribeNewsletterEvent;
use Woo\Newsletter\Events\UnsubscribeNewsletterEvent;
use Woo\Newsletter\Http\Requests\NewsletterRequest;
use Woo\Newsletter\Repositories\Interfaces\NewsletterInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\URL;

class PublicController extends Controller
{
    protected NewsletterInterface $newsletterRepository;

    public function __construct(NewsletterInterface $newsletterRepository)
    {
        $this->newsletterRepository = $newsletterRepository;
    }

    public function postSubscribe(NewsletterRequest $request, BaseHttpResponse $response)
    {
        $newsletter = $this->newsletterRepository->getFirstBy(['email' => $request->input('email')]);
        if (! $newsletter) {
            $newsletter = $this->newsletterRepository->createOrUpdate($request->input());

            event(new SubscribeNewsletterEvent($newsletter));
        }

        return $response->setMessage(__('Subscribe to newsletter successfully!'));
    }

    public function getUnsubscribe(int $id, Request $request, BaseHttpResponse $response)
    {
        if (! URL::hasValidSignature($request)) {
            abort(404);
        }

        $newsletter = $this->newsletterRepository->getFirstBy([
            'id' => $id,
            'status' => NewsletterStatusEnum::SUBSCRIBED,
        ]);

        if ($newsletter) {
            $newsletter->status = NewsletterStatusEnum::UNSUBSCRIBED;
            $this->newsletterRepository->createOrUpdate($newsletter);

            event(new UnsubscribeNewsletterEvent($newsletter));

            return $response
                ->setNextUrl(route('public.index'))
                ->setMessage(__('Unsubscribe to newsletter successfully'));
        }

        return $response
            ->setError()
            ->setNextUrl(route('public.index'))
            ->setMessage(__('Your email does not exist in the system or you have unsubscribed already!'));
    }
}
