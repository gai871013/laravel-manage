<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\SMSRecord;
use App\Models\SmsTemplate;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class CommonController extends Controller
{


    /**
     * 发送验证码 by gai871013 on 2016-11-25 16:59:10
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendSms(Request $request)
    {

        $data = $request->all();
        if (!isset($data['tel']) || !Helper::is_mobile($data['tel'])) {
            return $this->json(trans('common.40007'), 40007);
        }

        $type = $request['type'] ? $request['type'] : 'default';
        if (isset($request['id'])) {
            $sms_template = SmsTemplate::orderBy('id', 'desc')->where('type', $type)->where('admin_id', 1)->where('id', $request['id'])->first();
        } else {
            $sms_template = SmsTemplate::orderBy('id', 'desc')->where('type', $type)->where('admin_id', 1)->first();
        }
        $sms_arr = json_decode($sms_template, true);
        if (empty($sms_arr)) {
            return $this->json(trans('common.40008'), 40008);
        }

        // 目前只有这个运营商的接口
        if ($type == 'default') {

            // 判断上次发送验证码时间（3分钟）且使用状态
            $last_record = $lockRecord = SMSRecord::lockForUpdate()->orderBy('id', 'desc')->where('tel', $request['tel'])->first();
            $last_record = json_decode($last_record, true);
            if (!empty($last_record) && (time() - $last_record['ctime'] < 60) && $last_record['used'] == 0) {
                return $this->json(trans('common.40012'), 40012);
            }

            $username = $sms_arr['username'];
            $pwd = $sms_arr['password'];
            $password = md5($username . "" . md5($pwd));
            $mobile = $data['tel'];

            $code = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

            $content = sprintf($sms_arr['content'], $code);
            $url = $sms_arr['url'];
            $send_data = array(
                'username' => $username,
                'password' => $password,
                'mobile'   => $mobile,
                'content'  => $content
            );
            $result = $this->curlPost($url, $send_data);
            if ($result) {
                // 存储验证码
                if (empty($last_record)) {
                    $record = new SMSRecord();
                    $record->user_id = (isset($data['user_id']) ? $data['user_id'] : 0);
                    $record->tel = $request['tel'];
                    $record->code = $code;
                    $record->used = 0;
                    $record->ctime = time();
                    $record->cip = \EasyWeChat\Payment\get_client_ip();
                    $record->save();
                } else {
                    $lockRecord->code = $code;
                    $lockRecord->num = $last_record['num'] + 1;
                    $lockRecord->used = 0;
                    $lockRecord->ctime = time();
                    $lockRecord->save();
                }
                return $this->json(trans('common.40011'), 40011);
            } else {
                return $this->json(trans('common.40010'), 40010);
            }
        } else {
            return $this->json(trans('common.40009'), 40009);
        }
    }


    /**
     * 检测验证码是否正确/过期
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function checkCode(Request $request)
    {
        $userId = 2;

        $data = $request->all();
        if (!isset($data['tel']) || !Helper::is_mobile($data['tel'])) {
            return $this->json(trans('common.40007'), 40007);
        }

        $code = isset($request['code']) ? $request['code'] : '';
        return Helper::checkCode($data['tel'], $code, $userId);
    }

    /**
     * CURL POST
     * @param $url
     * @param array $data
     * @return mixed
     */
    public function curlPost($url, $data = array())
    {
        $response = new Client();
        return $response->post($url, ['form_params' => $data])->getBody();

        // region 弃用
        $param = http_build_query($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
        // endregion
    }

    public function getPlaceSuggestion(Request $request)
    {
        $data = $request->all();
        $keyword = isset($data['keyword']) ? $data['keyword'] : '郑州市';
        $res = Helper::placeSuggest($keyword);
        $res = json_decode($res, true);
        if (isset($res['status']) && $res['status'] == 0) {
            foreach ($res['result'] as $k => $v) {
                if (!isset($v['location'])) {
                    unset($res['result'][$k]);
                }
            }
        }
        return $this->ajaxReturn($res);
    }

    public function getWeatherInfo()
    {
        $params = [
            'key' => '5402ab6012842014a8d60a19f46a5640'
        ];
        $area = 'https://restapi.amap.com/v3/ip';
        $weather = 'https://restapi.amap.com/v3/weather/weatherInfo';

        $area_arr = array_merge($params,['ip' => request()->ip()]);

        $client = new Client();
        $area_info = $client->get($area . '?' . http_build_query($area_arr))->getBody();
        return $area_info;
    }

}
