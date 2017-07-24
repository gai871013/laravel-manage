<?php
/**
 * Created by @gai871013.
 * User: @gai871013
 * FileName: bzhServer/Helper.php
 * Date: 2017/1/13
 * Time: 15:19
 *
 *
 * ━━━━━━神兽出没━━━━━━
 *       ┏┓    ┏┓
 *      ┏┛┻━━━━┛┻┓
 *      ┃        ┃
 *      ┃    ━   ┃
 *      ┃  ┳┛ ┗┳ ┃
 *      ┃        ┃
 *      ┃    ┻   ┃
 *      ┃        ┃
 *      ┗━┓    ┏━┛  Code is far away from bug with the animal protecting
 *        ┃    ┃    神兽保佑,代码无bug
 *        ┃    ┃
 *        ┃    ┗━━━┓
 *        ┃        ┣┓
 *        ┃       ┏┛
 *        ┗┓┓┏━┳┓┏┛
 *         ┃┫┫ ┃┫┫
 *         ┗┻┛ ┗┻┛
 *
 * ━━━━━━感觉萌萌哒━━━━━━
 */

namespace App\Helpers;


use App\Models\SMSRecord;
use App\Models\Uploads;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class Helper
{

    const DICT = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const AK = 'rDQEhkggEKayeCo1Qa29v6fRn6qGMsYe';
    // 允许上传的格式
    const fileTypes = [
        'file' => ['doc', 'docx'],
        'picture' => ['jpg', 'jpeg', 'gif', 'png', 'bmp']
    ];


    /**
     * 左侧菜单
     * @return array|null
     */
    public static function leftMenu($guard = '', $enable = 1)
    {
        $user = auth($guard)->user();
        if ($user) {
            $navAll = \App\Models\AdminAction::orderBy('list_order', 'asc')
                ->orderBy('id', 'asc');
            if ($enable == 1) {
                $navAll = $navAll->where('enable', 1);
            }
            $navAll = $navAll->get()->toArray();
            $navs = [];
            if ($user->action_list != 'all') {
                $roleActionList = \App\Models\Role::find($user->role_id);
                if ($roleActionList->action_list == 'all') {
                    return $navAll;
                }
                $navTmp = array_unique(explode(',', $roleActionList->action_list));
                $parent_id = [];
                foreach ($navTmp as $k => $v) {
                    foreach ($navAll as $kk => $vv) {

                        $parent_id_tmp = 0;
                        if ($vv['id'] == $v) {
                            $navs[] = $navAll[$kk];
                            $parent_id_tmp = $navAll[$kk]['parent_id'];
                        }
                        if (!in_array($parent_id_tmp, $parent_id)) {
                            $parent_id[] = $parent_id_tmp;
                        }
                    }
                }

                unset($parent_id[0]);
                $parent_id = array_unique($parent_id);

                foreach ($parent_id as $k => $v) {
                    foreach ($navAll as $kk => $vv) {
                        if ($vv['id'] == $v) {
                            $navs[] = $navAll[$kk];
                        }
                    }
                }
                $navAll = $navs;
            }

        } else {
            $navAll = null;
        }

        return $navAll;
    }

    public static function actionUri($action, $parent_id = 0, $url = 'admin')
    {
        $tmp = [];
        $uri = [];
        foreach ((array)$action as $k => $v) {
            if ($v['parent_id'] == $parent_id) {
                $tmp[] = $v;
                $uri[] = $url . '/' . $v['code'];
                unset($action[$k]);
            }
        }


        if (!empty($action)) {
            foreach ($tmp as $k => $v) {
                $children = self::actionUri($action, $v['id'], $uri[$k]);
                if (!empty($children)) {
                    $uri = array_merge($uri, $children);
                }
            }
        }

        return $uri;
    }


    /**
     * 十进制数转换成62进制
     *
     * @param integer $num
     * @return string
     */
    public static function from10_to62($num)
    {
        $to = 62;
        $ret = '';
        do {
            $ret = self::DICT[bcmod($num, $to)] . $ret;
            $num = bcdiv($num, $to);
        } while ($num > 0);
        return $ret;
    }

    /**
     * 62进制数转换成十进制数
     *
     * @param string $num
     * @return string
     */
    public static function from62_to10($num)
    {
        $from = 62;
        $num = strval($num);
        $len = strlen($num);
        $dec = 0;
        for ($i = 0; $i < $len; $i++) {
            $pos = strpos(self::DICT, $num[$i]);
            $dec = bcadd(bcmul(bcpow($from, $len - $i - 1), $pos), $dec);
        }
        return $dec;
    }

    /**
     * 替换bcmath
     **/
    public static function cry62($n)
    {
        $base = 62;
        $ret = '';
        for ($t = floor(log10($n) / log10($base)); $t >= 0; $t--) {
            $a = floor($n / pow($base, $t));
            $ret .= substr(self::DICT, $a, 1);
            $n -= $a * pow($base, $t);
        }
        return $ret;
    }


    /**
     * 上传文件
     * @param Request $request
     * @param int $user_id
     * @param string $user_name
     * @param int $size
     * @return array
     */
    public static function uploadFile(Request $request, $user_id = 0, $user_name = '', $size = 10)
    {
        $fileUtil = new FileUtil();
//        logger($request->files);
        $dir = 'upload/' . date('Y') . '/' . date('md') . '/';
        $fileUtil->createDir($dir);

        $files = $request->file('file');
        $file_arr = [];

        if (is_array($files)) {
            foreach ($files as $k => $file) {
                if ($k > ($size - 1)) {
                    break;
                }
                if ($file->isValid()) {
                    $upload = self::upload($request, $file, $dir, $user_id);
                    $file_arr[] = json_decode($upload, true);
                }
            }
        } else {
            if ($files->isValid()) {
                $upload = self::upload($request, $files, $dir, $user_id);
                $file_arr[] = json_decode($upload, true);
            }
        }

        return $file_arr;


    }

    /**
     * 上传图片 2016-12-05 14:23:02 by gai871013
     * @param Request $request
     * @param $file
     * @param $dir
     * @param int $user_id
     * @param int $admin_id
     * @return Uploads
     */
    public static function upload(Request $request, $file, $dir, $user_id = 0, $admin_id = 0)
    {
        $extension = $file->guessClientExtension() ? $file->guessClientExtension() : '';
        if ($extension == '') {
            $extension = $file->getClientOriginalExtension();
        }
        if (empty($extension)) {
            $extension = $file->getClientOriginalName();
            $extension = explode('.', $extension);
            $extension = end($extension);
        }
//                    $extension = strtolower($file->guessClientExtension());
        $extension = strtolower($extension);
        $rdm = str_random(16);
        $fileName = $dir . date('His') . '_' . $rdm . '.' . $extension;

        $thumb = $dir . date('His') . '_thumb_' . $rdm . '.' . $extension;
        $file->move($dir, $fileName);
        // 保存缩略图
        $img = Image::make($fileName);
        $width = $img->width() * .35;
        $height = $img->height() * .35;
        $img->resize($width, $height);
        $img->save($thumb);
        // end
        $url = url($fileName);
        $upload = new Uploads();
        $upload->user_id = $user_id;
        $upload->admin_id = $admin_id;
        $upload->url = $url;
        $upload->path = $dir;
        $upload->type = $extension;
        $upload->original_name = $file->getClientOriginalName();
        $upload->size = $file->getClientSize();
        $upload->mime = $file->getClientMimeType();
        $upload->thumb = url($thumb);
        $upload->cip = $request->ip();
        $upload->created_at = date('Y-m-d H:i:s');
        $upload->updated_at = date('Y-m-d H:i:s');
        $upload->save();
        return $upload;

    }


    /*
     * 检测手机验证码有效性
     * @param string $mobile 手机号
     * @param string $code 验证码
     * @param int $userId 接口用户ID
     * @return array $return 返回验证码
     * 2016-8-4 09:17:16
     */
    public static final function checkCode($mobile = '', $code = '', $userId = 0)
    {
        if ($mobile == '' || !self::is_mobile($mobile)) {
            return self::returnArr(trans('common.40007'), 40007);
        }

        if ($code == '') {
            return self::returnArr(trans('common.40001', ['name' => trans('common.code')]), 40001);
        }

        if ($userId > 0) {
            $record = SMSRecord::orderBy('id', 'desc')->where('user_id', $userId)->where('tel', $mobile)->first();
        } else {
            $record = SMSRecord::orderBy('id', 'desc')->where('tel', $mobile)->first();
        }
        $record = json_decode($record, true);
        if (empty($record)) {
            return self::returnArr(trans('common.40013'), 40013);
        } elseif ($record['used'] == 1 || (time() - $record['ctime'] > 180)) {
            return self::returnArr(trans('common.40014'), 40014);
        } elseif ($code == $record['code']) {
            // 作废验证码
            $update = [
                'used' => 1
            ];
            SMSRecord::where('id', $record['id'])->update($update);
            return self::returnArr(trans('common.20000'), 20000);
        } else {
            return self::returnArr(trans('common.40015'), 40015);

        }
    }


    /**
     * 检测是否是手机号码
     * @param $mobile
     * @return bool
     */
    public static final function is_mobile($mobile)
    {
        $preg = "/^1[34578][0-9]{9}$/";
        if (preg_match($preg, $mobile)) {
            //验证通过
            return true;
        } else {
            //手机号码格式不对
            return false;
        }
    }

    /**
     * 删除空格和回车 2016-12-05 14:23:51 by gai871013
     * @param $str
     * @return mixed
     */
    public static function trimall($str)
    {
        $qian = array(" ", "　", "\t", "\n", "\r", "\r\n", "\n\r");
        return str_replace($qian, '', $str);
    }


    /*
     * @param string $message 消息内容
     * @param string $status_code 代码
     * @return json {}
     *
     */
    public static function returnArr($message = '', $status_code = 200)
    {
        return [
            'status_code' => $status_code,
            'message' => $message
        ];

    }


    /*
     * @param string $json JSON串
     * @return array
     *
     */
    public static function json2array($json)
    {
        return json_decode($json, true);
    }

    /*
     * @param string $message 消息内容
     * @param string $status_code 代码
     * @return json {}
     *
     */
    public static function json($message = '', $status_code = 200, $http_status = 200)
    {
        $return = [
            'status_code' => $status_code,
            'message' => $message
        ];

        return response()->json($return, $http_status);
    }

    /**
     * @param $lat1
     * @param $lng1
     * @param $lat2
     * @param $lng2
     * @return int
     */
    public static function getDistance($lat1, $lng1, $lat2, $lng2)
    {
        //将角度转为狐度
        $radLat1 = deg2rad($lat1);//deg2rad()函数将角度转换为弧度
        $radLat2 = deg2rad($lat2);
        $radLng1 = deg2rad($lng1);
        $radLng2 = deg2rad($lng2);
        $a = $radLat1 - $radLat2;
        $b = $radLng1 - $radLng2;
        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6378.137;
        return $s;
    }

    /**
     * @param $lat1
     * @param $lon1
     * @param $lat2
     * @param $lon2
     * @param float $radius 星球半径
     * @return float
     */
    public static function distance($lat1, $lon1, $lat2, $lon2, $radius = 6378.137)
    {
        $rad = floatval(M_PI / 180.0);
        $lat1 = floatval($lat1) * $rad;
        $lon1 = floatval($lon1) * $rad;
        $lat2 = floatval($lat2) * $rad;
        $lon2 = floatval($lon2) * $rad;
        $theta = $lon2 - $lon1;
        $dist = acos(sin($lat1) * sin($lat2) +
            cos($lat1) * cos($lat2) * cos($theta)
        );
        if ($dist < 0) {
            $dist += M_PI;
        }
        return $dist = $dist * $radius;
    }

    /**
     * 根据关键词获取建议
     * @param string $keyword
     * @return \Psr\Http\Message\StreamInterface
     */
    public static function placeSuggest($keyword = '郑州市')
    {
        $url = 'http://api.map.baidu.com/place/v2/suggestion';
        $params = [
            'query' => $keyword,
            'region' => '郑州市',
            'city_limit' => 'true',
            'output' => 'json',
            'ak' => self::AK,
            'limit' => 3
        ];
        $client = new Client();
        return $client->get($url, ['query' => $params])->getBody();
    }

    /**
     * 根据两个坐标获取距离 2017-5-11 16:50:21 by gai871013
     * @param $lat1
     * @param $lon1
     * @param $lat2
     * @param $lon2
     * @return \Psr\Http\Message\StreamInterface
     */
    public static function routeMatrix($lon1, $lat1, $lon2, $lat2)
    {
        $url = 'http://api.map.baidu.com/routematrix/v2/driving';
        $params = [
            'origins' => $lat1 . ',' . $lon1,
            'destinations' => $lat2 . ',' . $lon2,
            'output' => 'json',
            'ak' => self::AK,
        ];
        $client = new Client();
        return $client->get($url, ['query' => $params])->getBody();
    }

    public static function changePosition($coords, $from = 1, $to = 5)
    {
        $url = 'http://api.map.baidu.com/geoconv/v1/';
        $params = [
            'coords' => $coords,
            'ak' => self::AK,
            'from' => $from,
            'to' => $to
        ];
        $client = new Client();
        return $client->get($url, ['query' => $params])->getBody()->getContents();
    }

    public static function post($url, $data)
    {
        $postdata = http_build_query(
            $data
        );

        $opts = array('http' =>
            array(
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );
        $context = stream_context_create($opts);
        $result = file_get_contents($url, false, $context);
        return $result;
    }
}