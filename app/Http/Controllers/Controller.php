<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function ajaxReturn($data, $status_code = 200)
    {
//        return response()->json($data, $status_code, [], JSON_UNESCAPED_UNICODE);
        return response()->json($data, $status_code);
    }

    /*
     * @param string $message 消息内容
     * @param string $status_code 代码
     * @return json {}
     *
     */
    public function json($message = '', $status_code = 200, $http_status = 200)
    {
        $return = [
            'status_code' => $status_code,
            'message' => $message
        ];
        return $this->ajaxReturn($return, $http_status);
    }

}
