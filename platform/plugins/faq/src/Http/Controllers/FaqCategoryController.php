<?php

namespace Woo\Faq\Http\Controllers;

use Woo\Base\Events\BeforeEditContentEvent;
use Woo\Faq\Http\Requests\FaqCategoryRequest;
use Woo\Faq\Repositories\Interfaces\FaqCategoryInterface;
use Woo\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Woo\Faq\Tables\FaqCategoryTable;
use Woo\Base\Events\CreatedContentEvent;
use Woo\Base\Events\DeletedContentEvent;
use Woo\Base\Events\UpdatedContentEvent;
use Woo\Base\Http\Responses\BaseHttpResponse;
use Woo\Faq\Forms\FaqCategoryForm;
use Woo\Base\Forms\FormBuilder;

class FaqCategoryController extends BaseController
{
    protected FaqCategoryInterface $faqCategoryRepository;

    public function __construct(FaqCategoryInterface $faqCategoryRepository)
    {
        $this->faqCategoryRepository = $faqCategoryRepository;
    }

    public function index(FaqCategoryTable $table)
    {
        page_title()->setTitle(trans('plugins/faq::faq-category.name'));

        return $table->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/faq::faq-category.create'));

        return $formBuilder->create(FaqCategoryForm::class)->renderForm();
    }

    public function store(FaqCategoryRequest $request, BaseHttpResponse $response)
    {
        $faqCategory = $this->faqCategoryRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(FAQ_CATEGORY_MODULE_SCREEN_NAME, $request, $faqCategory));

        return $response
            ->setPreviousUrl(route('faq_category.index'))
            ->setNextUrl(route('faq_category.edit', $faqCategory->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(int $id, FormBuilder $formBuilder, Request $request)
    {
        $faqCategory = $this->faqCategoryRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $faqCategory));

        page_title()->setTitle(trans('plugins/faq::faq-category.edit') . ' "' . $faqCategory->name . '"');

        return $formBuilder->create(FaqCategoryForm::class, ['model' => $faqCategory])->renderForm();
    }

    public function update(int $id, FaqCategoryRequest $request, BaseHttpResponse $response)
    {
        $faqCategory = $this->faqCategoryRepository->findOrFail($id);

        $faqCategory->fill($request->input());

        $this->faqCategoryRepository->createOrUpdate($faqCategory);

        event(new UpdatedContentEvent(FAQ_CATEGORY_MODULE_SCREEN_NAME, $request, $faqCategory));

        return $response
            ->setPreviousUrl(route('faq_category.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(Request $request, int $id, BaseHttpResponse $response)
    {
        try {
            $faqCategory = $this->faqCategoryRepository->findOrFail($id);

            $this->faqCategoryRepository->delete($faqCategory);

            event(new DeletedContentEvent(FAQ_CATEGORY_MODULE_SCREEN_NAME, $request, $faqCategory));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    public function deletes(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $faqCategory = $this->faqCategoryRepository->findOrFail($id);
            $this->faqCategoryRepository->delete($faqCategory);
            event(new DeletedContentEvent(FAQ_CATEGORY_MODULE_SCREEN_NAME, $request, $faqCategory));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
