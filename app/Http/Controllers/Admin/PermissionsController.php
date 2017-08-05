<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminAction;
use App\Models\Role;
use Illuminate\Http\Request;
use Validator;

class PermissionsController extends Controller
{

    /**
     * 后台用户管理 2017-4-19 16:52:35
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAdminManage(Request $request)
    {
        $role = $request->input('role');
        $title = trans('admin.adminManage');
        $users = Admin::orderBy('id', 'desc');
        if (isset($role)) {
            $users = $users->where('role_id', $role);
        }
        $users = $users->paginate(env('PAGE_SIZE'));
        return view('admin.permissions.adminManage', compact('title', 'users'));
    }

    /**
     * 角色管理 2017-4-19 17:02:56
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getRoleManage(Request $request)
    {
        $title = trans('admin.roleManage');
        $roles = Role::orderBy('id', 'desc')->paginate(env('PAGE_SIZE'));
        return view('admin.permissions.roleManage', compact('title', 'roles'));
    }


    /**
     * 编辑角色 2017-6-23 17:46:18
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getRoleEdit(Request $request)
    {
        $role_id = $request->input('id');
        $title = trans('admin.roleEdit');
        $role = Role::where('id', $role_id)->first();
        $action_list = [];
        if (!empty($role)) {
            $action_list = explode(',', $role->action_list);
        }
        $admin_action = AdminAction::orderBy('list_order', 'asc')->orderBy('id', 'asc')->get();
        return view('admin.permissions.roleEdit', compact('title', 'role', 'admin_action', 'action_list'));
    }

    /**
     * 编辑角色 2017-6-26 09:30:16 by gai871013
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postRoleEdit(Request $request)
    {
        $data = $request->input('info');
        $data['action_code'] = isset($data['action_code']) ? $data['action_code'] : [];
        $data['action_list'] = implode(',', $data['action_code']);
        unset($data['action_code']);
        if ($data['id'] > 0) {
            Role::where('id', $data['id'])->update($data);
        } else {
            $role = new Role();
            $role->name = $data['name'];
            $role->describe = $data['describe'];
            $role->action_list = $data['action_list'];
            $role->save();
        }
        $next = $request->input('next');
        flash()->success(trans('admin.save') . trans('admin.success'));
        $next = empty($next) ? route('admin.roleManage') : $next;
        return redirect($next);
    }

    /**
     * 删除角色 2017-6-26 09:31:06 by gai871013
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getRoleDelete(Request $request)
    {
        $res = Role::where('id', $request->input('id'))->delete();
        $flash = flash();
        if ($res) {
            $flash->success(trans('admin.delete') . trans('admin.success'));
        } else {
            $flash->error(trans('admin.delete') . trans('admin.fail'));
        }
        return redirect()->route('admin.roleManage');
    }

    /**
     * 用户资料修改 2017-6-23 16:52:35
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getProfile(Request $request)
    {
        $id = $request->input('id');
        $title = trans('admin.profile');
        $user = isset($id) ? Admin::where('id', $id)->first() : auth('admin')->user();
        $next = $request->input('next');
        $roles = Role::orderBy('id', 'asc')->get();
        return view('admin.permissions.profile', compact('title', 'user', 'next', 'roles'));
    }

    /**
     * 编辑用户 2017-6-23 16:52:33
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postProfile(Request $request)
    {
        $user = $request->input('info');
        $next = $request->input('next');
        $validator = Validator::make($user, [
            'username' => 'required|unique:admins,username,' . $user['id'] . ',id',
            'email' => 'required|email|unique:admins,email,' . $user['id'] . ',id',
            'password' => 'confirmed',
//            'name' => 'required',
//            'nickname' => 'required',
        ]);
        if ($validator->fails()) {
            $err = $validator->errors()->all();
            flash(implode('<br>', $err))->error()->important();
            return redirect()->back();
        }

        unset($user['password_confirmation']);
        if (empty($user['password'])) {
            unset($user['password']);
        } else {
            $user['password'] = bcrypt($user['password']);
        }
        if ($user['id'] > 0) {
            Admin::where('id', $user['id'])->update($user);
        } else {
            if (empty($request->input('info')['password'])) {
                flash('新建用户必须填写密码')->error()->important();
                return redirect()->back();
            }
            $newUser = new Admin();
            $newUser->username = $user['username'];
            $newUser->email = $user['email'];
            $newUser->password = $user['password'];
            $newUser->name = $user['name'];
            $newUser->nickname = $user['nickname'];
            $newUser->role_id = (isset($user['role_id']) ? $user['role_id'] : 0);
            $newUser->save();
        }

        flash(trans('admin.save') . trans('admin.success'))->success();
        return redirect($next);
    }
}
