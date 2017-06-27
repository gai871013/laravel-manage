<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

$news = 'NewsController@';
$login = 'LoginController@';
$home = 'HomeController@';
$system = 'SystemController@';
$permissions = 'PermissionsController@';
$user = 'UserController@';
$upload = 'UploadController@';
$car = 'CarController@';
$journeyTask = 'JourneyTaskController@';
$weChat = 'WeChatController@';

Route::group(['middleware' => ['web']], function () use ($news, $home) {
    // 查看日志
    Route::get('laravel-logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
    // 系统首页
    Route::get('/', function () {
        return view('welcome');
    })->name('index');

    // 用户登录注册等
//    Auth::routes();
    Route::auth();

    // 发送验证码
    Route::post('sendSms', 'CommonController@sendSms')->name('sendSms');
    // 根据关键词获取搜索建议
    Route::any('placeSuggestion', 'CommonController@getPlaceSuggestion')->name('placeSuggestion');
});

// 微信端访问控制器
Route::group(['prefix' => 'wechat', 'middleware' => ['web', 'weChat.oauth:snsapi_userinfo']], function () use ($weChat) {
    Route::get('user', function () {
        $user = session('wechat.oauth_user'); // 拿到授权用户资料
        dd($user);
    });
    // 首页
    Route::get('/', $weChat . 'getIndex')->name('weChat.home');
});

// 需要登录之后才能访问
Route::group(['middleware' => ['web', 'auth']], function () use ($home) {
    // 前台用户首页
    Route::get('home', $home . 'index')->name('user.home');

});


// 登录后台
Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () use ($login) {
    // 登录页面
    Route::get('login', $login . 'showLoginForm')->name('admin.login');
    Route::post('login', $login . 'login');

});

// 后台用户管理
Route::group(
    ['prefix' => config('app.admin_path'), 'namespace' => 'Admin', 'middleware' => 'auth.admin:admin'],
    function () use ($home, $login, $system, $permissions, $user, $news, $upload, $car, $journeyTask) {
        // 后台首页
        Route::get('/', $home . 'index');
        Route::get('home', $home . 'index')->name('admin.home');
        // 退出登录
        Route::any('logout', $login . 'logout')->name('admin.logout');


        // 新闻管理
        Route::group(['prefix' => 'news'], function () use ($news) {
            // 分类管理
            Route::get('categoryManage', $news . 'getCategoryManage')->name('admin.news.category');
            // 新闻管理
            Route::get('newsList', $news . 'getNewsList')->name('admin.news.newsList');
            // 添加新闻
            Route::get('addNews/', $news . 'getAddNews')->name('admin.news.addNews');
            // 编辑新闻
            Route::get('editNews/{id?}', $news . 'getEditNews')->name('admin.news.editNews');
            // 删除新闻
            Route::get('deleteNews/{id}', $news . 'getDeleteNews')->name('admin.news.deleteNews');
            // 添加新闻单页
            Route::get('addSinglePage', $news . 'getAddSinglePage')->name('admin.news.addSinglePage');
        });
        // 系统相关
        Route::group(['prefix' => 'system'], function () use ($system) {
            // 系统设置
            Route::get('config', $system . 'getConfig')->name('admin.system.config');
            Route::post('config', $system . 'postConfig');
            // 菜单管理
            Route::get('menuManage', $system . 'getMenuManage')->name('admin.system.menuManage');
            Route::post('menuManage', $system . 'postMenuManage');
            // 添加菜单
            Route::get('menuEdit', $system . 'getMenuEdit')->name('admin.system.menuEdit');
            Route::post('menuEdit', $system . 'postMenuEdit');
            // 删除菜单
            Route::get('menuDelete', $system . 'getMenuDelete')->name('admin.system.menuDelete');
        });

        // 权限相关
        Route::group(['prefix' => 'permissions'], function () use ($permissions) {
            // 后台用户管理
            Route::get('adminManage', $permissions . 'getAdminManage')->name('admin.adminManage');
            // 角色管理
            Route::get('roleManage', $permissions . 'getRoleManage')->name('admin.roleManage');
            // 编辑角色
            Route::get('roleEdit', $permissions . 'getRoleEdit')->name('admin.roleEdit');
            Route::post('roleEdit', $permissions . 'postRoleEdit');
            // 删除角色
            Route::get('roleDelete', $permissions . 'getRoleDelete')->name('admin.roleDelete');

            // 用户资料修改
            Route::get('profile', $permissions . 'getProfile')->name('admin.user.profile');
            Route::post('profile', $permissions . 'postProfile');
        });

        // 用户管理
        Route::group(['prefix' => 'user'], function () use ($user) {
            // 前台用户管理
            Route::get('userManage', $user . 'getUserManage')->name('admin.userManage');
            // 编辑用户
            Route::get('user/edit', $user . 'getUserEdit')->name('admin.user.edit');
            Route::post('user/editAction', $user . 'postUserEdit')->name('admin.user.editAction');
            // 用户所在公司
            Route::get('companyList', $user . 'getCompanyList')->name('admin.companyList');

        });

        // 上传
        Route::post('upload', $upload . 'upload')->name('admin.upload');

    });