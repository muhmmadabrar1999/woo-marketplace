<?php

namespace Woo\Faq\Http\Controllers;

use Woo\Base\Events\BeforeEditContentEvent;
use Woo\Base\Traits\HasDeleteManyItemsTrait;
use Woo\Faq\Http\Requests\FaqRequest;
use Woo\Faq\Repositories\Interfaces\FaqInterface;
use Woo\Base\Http\Controllers\BaseController;
use Exception;
use Illuminate\Http\Request;
use Woo\Faq\Tables\FaqTable;
use Woo\Base\Events\CreatedContentEvent;
use Woo\Base\Events\DeletedContentEvent;
use Woo\Base\Events\UpdatedContentEvent;
use Woo\Base\Http\Responses\BaseHttpResponse;
use Woo\Faq\Forms\FaqForm;
use Woo\Base\Forms\FormBuilder;

class FaqController extends BaseController
{
    use HasDeleteManyItemsTrait;

    protected FaqInterface $faqRepository;

    public function __construct(FaqInterface $faqRepository)
    {
        $this->faqRepository = $faqRepository;
    }

    public function index(FaqTable $table)
    {
        page_title()->setTitle(trans('plugins/faq::faq.name'));

        return $table->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/faq::faq.create'));

        return $formBuilder->create(FaqForm::class)->renderForm();
    }

    public function store(FaqRequest $request, BaseHttpResponse $response)
    {
        $faq = $this->faqRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(FAQ_MODULE_SCREEN_NAME, $request, $faq));

        return $response
            ->setPreviousUrl(route('faq.index'))
            ->setNextUrl(route('faq.edit', $faq->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(int $id, FormBuilder $formBuilder, Request $request)
    {
        $faq = $this->faqRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $faq));

        page_title()->setTitle(trans('plugins/faq::faq.edit') . ' "' . $faq->question . '"');

        return $formBuilder->create(FaqForm::class, ['model' => $faq])->renderForm();
    }

    public function update(int $id, FaqRequest $request, BaseHttpResponse $response)
    {
        $faq = $this->faqRepository->findOrFail($id);

        $faq->fill($request->input());

        $this->faqRepository->createOrUpdate($faq);

        event(new UpdatedContentEvent(FAQ_MODULE_SCREEN_NAME, $request, $faq));

        return $response
            ->setPreviousUrl(route('faq.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(Request $request, int $id, BaseHttpResponse $response)
    {
        try {
            $faq = $this->faqRepository->findOrFail($id);

            $this->faqRepository->delete($faq);

            event(new DeletedContentEvent(FAQ_MODULE_SCREEN_NAME, $request, $faq));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    public function deletes(Request $request, BaseHttpResponse $response)
    {
        return $this->executeDeleteItems($request, $response, $this->faqRepository, FAQ_MODULE_SCREEN_NAME);
    }
}
