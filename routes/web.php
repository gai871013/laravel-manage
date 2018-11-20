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
$follower = 'FollowerController@';
$material = 'MaterialController@';
$menu = 'MenuController@';
$message = 'MessageController@';
$broadcast = 'BroadcastController@';


Route::group(['middleware' => ['web']], function () use ($news, $home) {
    // 查看日志
    Route::get('laravel-logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
    // 系统首页
    Route::get('/', $home . 'getIndex')->name('index');

    // 用户登录注册等
//    Auth::routes();
    Route::auth();

    // 发送验证码
    Route::post('sendSms', 'CommonController@sendSms')->name('sendSms');
    // 根据关键词获取搜索建议
    Route::any('placeSuggestion', 'CommonController@getPlaceSuggestion')->name('placeSuggestion');
    // 分类页面
    Route::get('category/{id}.html', $news . 'getCategory')->name('category');
    // 内容页
    Route::get('show/{id}.html', $news . 'getShow')->name('show');
    // 单页
    Route::get('page/{id}.html', $news . 'getPage')->name('page');

    Route::get('weather','CommonController@getWeatherInfo')->name('weather');
});

// 微信端访问控制器
Route::group(['prefix' => 'wechat', 'middleware' => ['web', 'weChat.oauth:snsapi_userinfo']], function () use ($weChat) {
    //Route::get('user', function () {
    //    $user = session('wechat.oauth_user'); // 拿到授权用户资料
    //    dd($user);
    //});
    // 首页
    Route::get('/', $weChat . 'getIndex')->name('weChat.home');
});
// 微信服务
Route::any('WeChat', 'WeChatServeController@handleMessage');

// 需要登录之后才能访问
Route::group(['middleware' => ['web', 'auth']], function () use ($home) {
    // 前台用户首页
    Route::get('home', $home . 'index')->name('user.home');

});


// 登录后台
Route::group(['prefix' => config('app.admin_path'), 'namespace' => 'Admin'], function () use ($login) {
    // 登录页面
    Route::get('login', $login . 'showLoginForm')->name('admin.login');
    Route::post('login', $login . 'login');
    // 登录背景
    Route::get('img', $login . 'bing')->name('admin.login.img');

});

// 后台用户管理
Route::group(
    ['prefix' => config('app.admin_path'), 'namespace' => 'Admin', 'middleware' => ['auth.admin:admin', 'admin.log']],
    function () use ($broadcast, $message, $menu, $material, $follower, $home, $login, $system, $permissions, $user, $news, $upload, $car, $journeyTask) {
        // 后台首页
        Route::get('/', $home . 'index');
        Route::get('home', $home . 'index')->name('admin.home');
        // 退出登录
        Route::any('logout', $login . 'logout')->name('admin.logout');
        // 清除缓存
        Route::get('clearCaches', $home . 'getClearCaches')->name('admin.clearCaches');


        // 新闻管理
        Route::group(['prefix' => 'news'], function () use ($news) {
            // 分类管理
            Route::get('categories', $news . 'getCategories')->name('admin.news.categories');
            // 分类排序
            Route::post('categoriesSort', $news . 'postCategoriesSort')->name('admin.news.categories.sort');
            // 编辑分类
            Route::get('categoryEdit', $news . 'getCategoryEdit')->name('admin.news.category.edit');
            Route::post('categoryEdit', $news . 'postCategoryEdit');
            // 删除分类
            Route::get('categoryDelete', $news . 'getCategoryDelete')->name('admin.news.category.delete');

            // 新闻管理
            Route::get('', $news . 'getNewsList')->name('admin.news.newsListIndex');
            Route::get('newsList', $news . 'getNewsList')->name('admin.news.newsList');
            // 编辑新闻
            Route::get('newsEdit', $news . 'getNewsEdit')->name('admin.news.newsEdit');
            Route::post('newsEdit', $news . 'postNewsEdit');
            // 删除新闻
            Route::get('newsDelete', $news . 'getNewsDelete')->name('admin.news.newsDelete');
            // 新闻单页
            Route::get('pages', $news . 'getSinglePage')->name('admin.news.singlePage');
            // 评论管理
            Route::get('comment', $news . 'getComment')->name('admin.news.comment');
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
            // 操作记录
            Route::get('operationLogs', $system . 'getOperationLogs')->name('admin.system.operationLogs');
            // 清除记录
            Route::get('operationLogsClear', $system . 'getOperationLogsClear')->name('admin.system.operationLogsClear');
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
        });

        // 微信相关管理
        Route::group(['prefix' => 'WeChat'], function () use ($broadcast, $message, $menu, $material, $follower) {

            // 微信用户管理
            Route::group(['prefix' => 'follower'], function () use ($follower) {
                // 列表
                Route::get('lists', $follower . 'getFollowerLists')->name('admin.follower');
                // 微信用户列表刷新
                Route::get('refresh', $follower . 'getFollowerRefresh')->name('admin.follower.refresh');
                // 微信用户详情更新
                Route::get('refreshDetail', $follower . 'getFollowerRefreshDetail')->name('admin.follower.refreshDetail');
                // 备注管理
                Route::any('remark', $follower . 'getFollowerRemark')->name('admin.follower.remark');
                // 加入、取消黑名单
                Route::get('black', $follower . 'getFollowerBlack')->name('admin.follower.black');
                // 更新黑名单列表
                Route::get('blackLists', $follower . 'getFollowerBlackLists')->name('admin.follower.blackLists');
                // 标签管理
                Route::get('tags', $follower . 'getTags')->name('admin.follower.tags');
                // 从服务器更新标签
                Route::get('tagUpdate', $follower . 'getTagUpdate')->name('admin.follower.tagUpdate');
                // 编辑标签
                Route::get('tagEdit', $follower . 'getTagEdit')->name('admin.follower.tagEdit');
                // 删除标签
                Route::get('tagDelete', $follower . 'getTagDelete')->name('admin.follower.tagDelete');
                // 分组管理
                Route::get('groups', $follower . 'getGroups')->name('admin.follower.groups');
                // 从服务器更新标签
                Route::get('groupUpdate', $follower . 'getGroupUpdate')->name('admin.follower.groupUpdate');
                // 编辑标签
                Route::get('groupEdit', $follower . 'getGroupEdit')->name('admin.follower.groupEdit');
                // 删除标签
                Route::get('groupDelete', $follower . 'getGroupDelete')->name('admin.follower.groupDelete');
            });

            // 素材管理
            Route::group(['prefix' => 'material'], function () use ($material) {
                // 永久素材
                Route::get('forever', $material . 'getForeverLists')->name('admin.material.forever');
                // 临时素材
                Route::get('temporary', $material . 'getTemporaryLists')->name('admin.material.temporary');
            });

            // 菜单管理
            Route::group(['prefix' => 'menu'], function () use ($menu) {
                Route::get('/', $menu . 'getIndex')->name('admin.WeChat.menu');
            });

            // 自动回复
            Route::group(['prefix' => 'message'], function () use ($message) {
                Route::get('/', $message . 'getIndex')->name('admin.WeChat.message');
                // 关注回复
                Route::get('subscribe', $message . 'getSubscribe')->name('admin.WeChat.message.subscribe');
            });

            // 消息群发
            Route::group(['prefix' => 'broadcast'], function () use ($broadcast) {
                Route::get('/', $broadcast . 'getIndex')->name('admin.WeChat.broadcast');
            });
        });

        // 上传
        Route::post('upload', $upload . 'upload')->name('admin.upload');

    });