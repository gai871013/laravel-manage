<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    //

    /**
     * 上传文件并返回文件path和url
     * @param Request $request
     * @return array
     */
    public function upload(Request $request)
    {
        $path = 'upload/' . date('Y/md');
        $res = $request->file('upload_file')->store($path);
        $return = [
            'status_code' => 20001,
            'message' => trans('common.20001'),
            'filename' => $res,
            'url' => \Storage::url($res)
        ];
        return $return;
    }
}
