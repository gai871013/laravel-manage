<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo;
    protected $username;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except(['logout', 'showLoginForm']);
        $this->username = config('admin.global.username');
        $this->redirectTo = config('app.admin_path') . '/home';
    }

    /**
     * 登录用户名
     * @return mixed
     */
    public function username()
    {
        return $this->username;
    }

    /**
     * 重写登录视图页面
     * @author gai871013
     * @date   2017-07-25T23:06:16+0800
     * @return [type]                   [description]
     */
    public function showLoginForm(Request $request)
    {
        $index = (int)$request->input('index');
        $auth = $this->guard()->check();
        if ($auth) {
            flash()->error('您已经登陆，如需重新登陆请先退出')->important();
            return redirect()->route('admin.home');
        }
        $img = asset('images/bg01.png');
        return view('admin.login.index', compact('img', 'index'));
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            $self = auth('admin')->user();
            $update = ['last_login' => $self->updated_at, 'ip' => $request->ip(), 'login_num' => $self->login_num + 1, 'last_ip' => $self->ip];
            Admin::where('id', $self->id)->update($update);
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * 自定义认证驱动
     * @author 晚黎
     * @date   2016-09-05T23:53:07+0800
     * @return [type]                   [description]
     */
    protected function guard()
    {
        return auth()->guard('admin');
    }

    public function logout(Request $request)
    {

        $this->guard()->logout();
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect(route('admin.login'));
    }
}