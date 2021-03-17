<?php

namespace App\Http\Controllers;

use Exception;
use Gai871013\IpLocation\Facades\IpLocation;
use Illuminate\Http\Request;

class IpController extends Controller
{
    public function index()
    {
        $title = 'IP地址查询';
        $ip    = request()->ip();
        $info  = $this->getIp();
        return view('ip', compact('info', 'ip', 'title'));
    }

    public function getIp()
    {
        $ip = request()->input('ip') ?? request()->ip();
        try {
            return ['code' => 0, 'msg' => '获取成功', 'data' => IpLocation::getLocation($ip)];
        } catch (Exception $e) {
            return ['code' => 1, 'msg' => $e->getMessage()];
        }
    }
}
