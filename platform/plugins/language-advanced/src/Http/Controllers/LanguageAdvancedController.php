<?php

namespace Woo\LanguageAdvanced\Http\Controllers;

use Woo\Base\Events\UpdatedContentEvent;
use Woo\Base\Http\Controllers\BaseController;
use Woo\Base\Http\Responses\BaseHttpResponse;
use Woo\LanguageAdvanced\Http\Requests\LanguageAdvancedRequest;
use Woo\LanguageAdvanced\Supports\LanguageAdvancedManager;

class LanguageAdvancedController extends BaseController
{
    public function save(int $id, LanguageAdvancedRequest $request, BaseHttpResponse $response)
    {
        $model = $request->input('model');

        if (! class_exists($model)) {
            abort(404);
        }

        $data = (new $model())->findOrFail($id);

        LanguageAdvancedManager::save($data, $request);

        do_action(LANGUAGE_ADVANCED_ACTION_SAVED, $data, $request);

        event(new UpdatedContentEvent('', $request, $data));

        return $response
            ->setMessage(trans('core/base::notices.update_success_message'));
    }
}
