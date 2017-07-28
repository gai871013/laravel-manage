<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use function EasyWeChat\Payment\get_client_ip;

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
        $img = $this->bing($request);
        return view('admin.login.index', compact('img', 'index'));
    }

    /**
     * 获取背景图片（来源为bing）
     * @param Request $request
     * @return string
     */
    public function bing(Request $request)
    {
        //设置bing参数
        $idx = (int)$request->input('index');
        $download = isset($_GET['download']) ? true : false;
        $domain = 'http://cn.bing.com';
        $fun = '/HPImageArchive.aspx';
        $param = array('format' => 'js', 'n' => 1, 'pid' => 'hd', 'video' => 1);
        $param['nc'] = time();
        $param['idx'] = $idx;
        $url = $domain . $fun . '?' . http_build_query($param);
        $dir = 'bing/' . date('Y-m', strtotime("-" . $idx . " day"));
        // json目录及文件名称
        $day = date('Y-m-d', strtotime("-" . $idx . " day"));
        $jsonDir = $dir . '/json/';
        // video
        $videoDir = $dir . '/mp4/';

        $storage_path = storage_path('app/public/' . $dir);
        if (!is_dir($storage_path . '/json')) {
            Storage::put($dir . '/json/.gitignore', '');
        }
        // 获取idx天前的json数据
        // 首先从本地获取
        foreach (scandir($storage_path . '/json') as $v) {
            if ($v == $day . '.json') {
                $url = $storage_path . '/json/' . $day . '.json';
            }
        }
        $curl = file_get_contents($url);
        $content = json_decode($curl, true);
        // 获取图片路径
        $img = $domain . $content['images'][0]['url'];
        // 获取图片名称
        $fileName = explode('/', $img);
        $fileNmaeStr = end($fileName);
        // 输出图片

        // 获取目录下所有文件
        $files = scandir($storage_path);
        if (!in_array($fileNmaeStr, $files)) {
            $IMG = file_get_contents($img);
            Storage::put($dir . '/' . $fileNmaeStr, $IMG);
            Storage::put($jsonDir . $day . '.json', $curl);
        }
        return Storage::url($dir . '/' . $fileNmaeStr);

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
            $update = ['last_login' => $self->updated_at, 'ip' => get_client_ip(), 'login_num' => $self->login_num + 1, 'last_ip' => $self->ip];
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