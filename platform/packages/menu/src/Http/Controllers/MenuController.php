<?php

namespace Woo\Menu\Http\Controllers;

use Woo\Base\Events\BeforeEditContentEvent;
use Woo\Base\Events\CreatedContentEvent;
use Woo\Base\Events\DeletedContentEvent;
use Woo\Base\Events\UpdatedContentEvent;
use Woo\Base\Forms\FormBuilder;
use Woo\Base\Http\Controllers\BaseController;
use Woo\Base\Http\Responses\BaseHttpResponse;
use Woo\Menu\Forms\MenuForm;
use Woo\Menu\Http\Requests\MenuNodeRequest;
use Woo\Menu\Http\Requests\MenuRequest;
use Woo\Menu\Models\Menu as MenuModel;
use Woo\Menu\Repositories\Eloquent\MenuRepository;
use Woo\Menu\Repositories\Interfaces\MenuInterface;
use Woo\Menu\Repositories\Interfaces\MenuLocationInterface;
use Woo\Menu\Repositories\Interfaces\MenuNodeInterface;
use Woo\Menu\Tables\MenuTable;
use Woo\Support\Services\Cache\Cache;
use Exception;
use Illuminate\Cache\CacheManager;
use Illuminate\Http\Request;
use Menu;
use stdClass;

class MenuController extends BaseController
{
    protected MenuInterface $menuRepository;

    protected MenuNodeInterface $menuNodeRepository;

    protected MenuLocationInterface $menuLocationRepository;

    protected Cache $cache;

    public function __construct(
        MenuInterface $menuRepository,
        MenuNodeInterface $menuNodeRepository,
        MenuLocationInterface $menuLocationRepository,
        CacheManager $cache
    ) {
        $this->menuRepository = $menuRepository;
        $this->menuNodeRepository = $menuNodeRepository;
        $this->menuLocationRepository = $menuLocationRepository;
        $this->cache = new Cache($cache, MenuRepository::class);
    }

    public function index(MenuTable $table)
    {
        page_title()->setTitle(trans('packages/menu::menu.name'));

        return $table->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('packages/menu::menu.create'));

        return $formBuilder->create(MenuForm::class)->renderForm();
    }

    public function store(MenuRequest $request, BaseHttpResponse $response)
    {
        $menu = $this->menuRepository->getModel();

        $menu->fill($request->input());
        $menu->slug = $this->menuRepository->createSlug($request->input('name'));
        $menu = $this->menuRepository->createOrUpdate($menu);

        $this->cache->flush();

        event(new CreatedContentEvent(MENU_MODULE_SCREEN_NAME, $request, $menu));

        $this->saveMenuLocations($menu, $request);

        return $response
            ->setPreviousUrl(route('menus.index'))
            ->setNextUrl(route('menus.edit', $menu->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    protected function saveMenuLocations(MenuModel $menu, Request $request): bool
    {
        $locations = $request->input('locations', []);

        $this->menuLocationRepository->deleteBy([
            'menu_id' => $menu->id,
            ['location', 'NOT_IN', $locations],
        ]);

        foreach ($locations as $location) {
            $menuLocation = $this->menuLocationRepository->firstOrCreate([
                'menu_id' => $menu->id,
                'location' => $location,
            ]);

            event(new CreatedContentEvent(MENU_LOCATION_MODULE_SCREEN_NAME, $request, $menuLocation));
        }

        return true;
    }

    public function edit(int $id, FormBuilder $formBuilder, Request $request)
    {
        page_title()->setTitle(trans('packages/menu::menu.edit'));

        $oldInputs = old();
        if ($oldInputs && $id == 0) {
            $oldObject = new stdClass();
            foreach ($oldInputs as $key => $row) {
                $oldObject->$key = $row;
            }
            $menu = $oldObject;
        } else {
            $menu = $this->menuRepository->findOrFail($id);
        }

        event(new BeforeEditContentEvent($request, $menu));

        return $formBuilder->create(MenuForm::class, ['model' => $menu])->renderForm();
    }

    public function update(MenuRequest $request, int $id, BaseHttpResponse $response)
    {
        $menu = $this->menuRepository->firstOrNew(compact('id'));

        $menu->fill($request->input());
        $this->menuRepository->createOrUpdate($menu);
        event(new UpdatedContentEvent(MENU_MODULE_SCREEN_NAME, $request, $menu));

        $this->saveMenuLocations($menu, $request);

        $deletedNodes = ltrim($request->input('deleted_nodes', ''));
        if ($deletedNodes) {
            $deletedNodes = explode(' ', ltrim($request->input('deleted_nodes', '')));
            $this->menuNodeRepository->deleteBy([
                ['id', 'IN', $deletedNodes],
                ['menu_id', '=', $menu->id],
            ]);
        }

        $menuNodes = Menu::recursiveSaveMenu(json_decode($request->input('menu_nodes'), true), $menu->id, 0);

        $request->merge(['menu_nodes', json_encode($menuNodes)]);

        $this->cache->flush();

        return $response
            ->setPreviousUrl(route('menus.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(Request $request, int $id, BaseHttpResponse $response)
    {
        try {
            $menu = $this->menuRepository->findOrFail($id);
            $this->menuNodeRepository->deleteBy(['menu_id' => $menu->id]);
            $this->menuRepository->delete($menu);

            event(new DeletedContentEvent(MENU_MODULE_SCREEN_NAME, $request, $menu));

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
            $menu = $this->menuRepository->findOrFail($id);
            $this->menuNodeRepository->deleteBy(['menu_id' => $menu->id]);
            $this->menuRepository->delete($menu);
            event(new DeletedContentEvent(MENU_MODULE_SCREEN_NAME, $request, $menu));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    public function getNode(MenuNodeRequest $request, BaseHttpResponse $response)
    {
        $data = (array)$request->input('data', []);

        $row = $this->menuNodeRepository->getModel();
        $row->fill($data);
        $row = Menu::getReferenceMenuNode($data, $row);
        $row->save();

        event(new CreatedContentEvent(MENU_NODE_MODULE_SCREEN_NAME, $request, $row));

        $html = view('packages/menu::partials.node', compact('row'))->render();

        return $response
            ->setData(compact('html'))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }
}
