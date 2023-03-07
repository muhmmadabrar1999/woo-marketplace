<?php

namespace Woo\AuditLog\Http\Controllers;

use Woo\AuditLog\Repositories\Interfaces\AuditLogInterface;
use Woo\AuditLog\Tables\AuditLogTable;
use Woo\Base\Events\DeletedContentEvent;
use Woo\Base\Http\Controllers\BaseController;
use Woo\Base\Http\Responses\BaseHttpResponse;
use Woo\Base\Traits\HasDeleteManyItemsTrait;
use Exception;
use Illuminate\Http\Request;

class AuditLogController extends BaseController
{
    use HasDeleteManyItemsTrait;

    protected AuditLogInterface $auditLogRepository;

    public function __construct(AuditLogInterface $auditLogRepository)
    {
        $this->auditLogRepository = $auditLogRepository;
    }

    public function getWidgetActivities(BaseHttpResponse $response, Request $request)
    {
        $limit = (int)$request->input('paginate', 10);
        $limit = $limit > 0 ? $limit : 10;

        $histories = $this->auditLogRepository
            ->advancedGet([
                'with' => ['user'],
                'order_by' => ['created_at' => 'DESC'],
                'paginate' => [
                    'per_page' => $limit,
                    'current_paged' => (int)$request->input('page', 1),
                ],
            ]);

        return $response
            ->setData(view('plugins/audit-log::widgets.activities', compact('histories', 'limit'))->render());
    }

    public function index(AuditLogTable $dataTable)
    {
        page_title()->setTitle(trans('plugins/audit-log::history.name'));

        return $dataTable->renderTable();
    }

    public function destroy(Request $request, int $id, BaseHttpResponse $response)
    {
        try {
            $log = $this->auditLogRepository->findOrFail($id);
            $this->auditLogRepository->delete($log);

            event(new DeletedContentEvent(AUDIT_LOG_MODULE_SCREEN_NAME, $request, $log));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $ex) {
            return $response
                ->setError()
                ->setMessage($ex->getMessage());
        }
    }

    public function deletes(Request $request, BaseHttpResponse $response)
    {
        return $this->executeDeleteItems($request, $response, $this->auditLogRepository, AUDIT_LOG_MODULE_SCREEN_NAME);
    }

    public function deleteAll(BaseHttpResponse $response)
    {
        $this->auditLogRepository->getModel()->truncate();

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
