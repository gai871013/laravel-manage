<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth.admin:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /*$people = [];
        for($i=1;$i<=100;$i++){
            $people[$i] = 100;
        }
        $day = 365*10;  //$day天后的执行结果
        $end_time = $day*24*60;
        for($start=0;$start<$end_time;$start+=60){

            foreach($people as $k=>$v){
                if($v>0){
                    $people[$k] --;
                    $id = rand(1,100);
                    $people[$id] ++;
                }
            }
        }

        $a = $b = $c = 0;
        $a_name = $b_name = $c_name = '';
        foreach($people as $k=>$v){
            if($v>100){
                $a ++;
                $a_name .= $k.',';
            }elseif($v == 100){
                $b ++;
                $b_name .= $k.',';
            }else{
                $c ++;
                $c_name .= $k.',';
            }
        }

        echo '大于100的人数:'.$a.'人,是['.$a_name.']';
        echo "<br/>";
        echo '等于于100的人数:'.$b.'人,是['.$b_name.']';
        echo "<br/>";
        echo '小于100的人数:'.$c.'人,是['.$c_name.']';
        echo "<br/>";*/
        $title = '管理后台首页';
        /*Log::emergency($title);
        Log::alert($title);
        Log::critical($title);
        Log::error($title);
        Log::warning($title);
        Log::notice($title);
        Log::info($title);
        Log::debug($title);*/
        $user = auth('admin')->user();
        return view('admin.home', compact('title', 'user'));
//        var_dump($request->session()->all());
//        dd('后台首页，当前用户名：'.auth('admin')->user()->username);
    }

    /**
     * 清理缓存 2017-7-17 16:41:20 by gai871013
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getClearCaches(Request $request)
    {
        $cache_dir = storage_path('framework/cache/');
        $sessions_dir = storage_path('framework/sessions/');
        $views_dir = storage_path('framework/views');
        $this->delete($cache_dir);
        $this->delete($sessions_dir);
        $this->delete($views_dir);
        flash('清理完成')->success();
        return redirect()->back();
    }

    /**
     * 删除文件/文件夹
     * @param $dir
     */
    private function delete($dir)
    {
        $dir_arr = scandir($dir);
        foreach ($dir_arr as $k => $v) {
            if ($v == '.' || $v == '..' || $v == '.gitignore') {
                continue;
            }
            if (is_dir($dir . '/' . $v)) {
                rmdir($dir . '/' . $v);
            } else {
                @unlink($dir . '/' . $v);
            }
        }
    }
}