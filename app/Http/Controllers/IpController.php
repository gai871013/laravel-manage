<?php

namespace App\Http\Controllers;

use Exception;
use Gai871013\IpLocation\Facades\IpLocation;
use Gai871013\IpLocation\ipip\db\City;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Ip2Region;

class IpController extends Controller
{
    /**
     * @return array|false|Factory|Application|View|mixed
     */
    public function index()
    {
        $title = 'IP地址查询';
        $ip    = request()->ip();
        $info  = $this->getIp();
        return view('ip', compact('info', 'ip', 'title'));
    }

    public function getIp()
    {
        $ip2region = new Ip2Region();
        $ip        = request()->input('ip') ?? request()->ip();
        $res       = IpLocation::getLocation($ip);
        dd($ip2region->binarySearch($ip));
        try {
            return ['code' => 0, 'msg' => '获取成功', 'data' => $res, 'ipip' => (new City())->find($ip), 'ip2region' => $ip2region->btreeSearch($res['ip'])];
        } catch (Exception $e) {
            return ['code' => 1, 'msg' => $e->getMessage()];
        }
    }
}
