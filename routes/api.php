<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['namespace' => 'Api'], function () {

    // 获取全局token值
    Route::get('token', 'MiniProgramController@getToken');
    // 保存formid
    Route::post('saveFormIds', 'MiniProgramController@saveFormIds');
    // 保存用户信息
    Route::post('saveUserInfo', 'MiniProgramController@saveUserInfo');
    // 保存用户详情
    Route::post('saveUserDetail', 'MiniProgramController@saveUserDetail');
    // 获取好友列表
    Route::get('friendLists', 'MiniProgramController@getFriendLists');
    // 获取用户信息
    Route::get('userInfo', 'MiniProgramController@getUserInfo');
    // 收藏&取消收藏
    Route::get('card', 'MiniProgramController@getCard');

});
//
//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
