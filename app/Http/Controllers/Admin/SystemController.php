<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAction;
use Illuminate\Http\Request;

class SystemController extends Controller
{
    //

    /**
     * 查看配置 2017-4-19 10:55:29
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getConfig()
    {
        return view('admin.system.config');
    }

    /**
     * 修改配置 2017-4-19 11:55:13
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function postConfig(Request $request)
    {
        $data = $request->all();
        $validator = \Validator::make($data, [
            'BASE64_APP_NAME' => 'required|min:1',
            'BASE64_APP_TITLE' => 'required',
            'BASE64_APP_DESCRIPTION' => 'required',
            'BASE64_APP_KEYWORDS' => 'required',
            'APP_SERVICE_QQ' => 'required',
            'APP_SERVICE_EMAIL' => 'required|email',
            'APP_SERVICE_PHONE' => 'required',
            'SHOP_REG_CLOSED' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect(route('admin.system.config'))
                ->withErrors($validator)
                ->withInput();
        }

        unset($data['_token']);
        modifyEnv($data);
        $next = route('admin.system.config');
        $sec = 1;
        return view('info', compact('next', 'sec'));
    }

    /**
     * 菜单管理
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getMenuManage(Request $request)
    {
        $title = trans('admin.menuManage');
        $menu = AdminAction::orderBy('list_order', 'asc')->get()->toArray();
        $menu = $this->menuFormat($menu);
//        dd($menu);
        return view('admin.system.menu', compact('title', 'menu'));
    }

    /**
     * 添加菜单 2017-4-25 10:25:58
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getMenuEdit(Request $request)
    {
        $id = $request->input('id');
        $title = trans('admin.addMenu');
        $menu = AdminAction::where('id', $id)->first();
        $menuAll = $this->menuFormat(AdminAction::orderBy('list_order', 'asc')->get()->toArray());
        return view('admin.system.menuEdit', compact('title', 'menu', 'menuAll', 'request'));
    }

    /**
     * 格式化菜单 2017-6-26 16:23:04 by gai871013
     * @param $action
     * @return mixed
     */
    private function menuFormat($action, $parent_id = 0)
    {
        $tmp = [];
        foreach ($action as $k => $v) {
            if ($v['parent_id'] == $parent_id) {
                $tmp[] = $v;
                unset($action[$k]);
            }
        }

        if (!empty($action)) {
            foreach ($tmp as $k => $v) {
                $children = $this->menuFormat($action, $v['id']);
                if (!empty($children)) {
                    $tmp[$k]['children'] = $children;
                }
            }
        }
        return $tmp;
    }

    /**
     * 删除菜单 2017-6-26 16:36:14 by gai871013
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getMenuDelete(Request $request)
    {
        $id = $request->input('id');
        AdminAction::where('id', $id)->delete();
        AdminAction::where('parent_id', $id)->delete();
        flash()->success(trans('admin.delete') . trans('admin.success'));
        return redirect()->back();
    }

    /**
     * 保存菜单 2017-6-26 17:00:15 by gai871013
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postMenuManage(Request $request)
    {
        $data = $request->input('menu');
        if ($data)
            $this->updateMenu($data);

        flash()->success(trans('admin.save') . trans('admin.success'));
        return redirect()->route('admin.system.menuManage');
    }

    /**
     * 更新菜单 2017-6-26 17:06:21 by gai871013
     * @param $data
     * @param int $sort
     * @param int $parent_id
     */
    public function updateMenu($data, &$sort = 1, $parent_id = 0)
    {

        $data = is_array($data) ? $data : json_decode($data, true);
        foreach ($data as $v) {
            AdminAction::where('id', $v['id'])->update(['list_order' => $sort, 'parent_id' => $parent_id]);
            $sort++;
            if (isset($v['children'])) {
                $this->updateMenu($v['children'], $sort, $v['id']);
            }
        }

//        return true;
    }

    /**
     *
     * @param Request $request
     * @return array|string
     */
    public function postMenuEdit(Request $request)
    {
        $data = $request->input('info');
        $data['icon'] = mb_strcut($data['icon'], 3);
        foreach ($data as $k => $v) {
            if ($v == null) {
                unset($data[$k]);
            }
        }
        if ($data['id'] > 0) {
            AdminAction::where('id', $data['id'])->update($data);
        } else {
            unset($data['id']);
            $menu = new AdminAction();
            $menu->save();
            AdminAction::where('id', $menu->id)->update($data);
        }
        flash()->success(trans('admin.save') . trans('admin.success'));
        return redirect()->route('admin.system.menuManage');
    }
}
