<?php

namespace App\Http\Controllers;

use EasyWeChat\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WeChatServeController extends Controller
{
    /**
     * 处理用户动作及借口验证配置 2017-8-7 12:05:24 by gai871013
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response|string
     */
    public function handleMessage(Request $request, Application $app)
    {
        $data = $request->all();
        if (empty($data)) {
            return '';
        }
        Log::info(json_encode($data));
        $server = $app->server;
        $server->setMessageHandler(function ($message) {
            Log::info(json_encode($message));
            $msgType = $message->MsgType;
            $message->ToUserName;    //接收方帐号（该公众号 ID）
            $openid = $message->FromUserName;  //发送方帐号（OpenID, 代表用户的唯一标识）
            $time = $message->CreateTime;    //消息创建时间（时间戳）
            $message->MsgId;         //消息 ID（64位整型）
            if ($msgType == 'event') {# 事件消息...
                Log::info($message->Event);
                $message->Event;      // 事件类型 （如：subscribe(订阅)、unsubscribe(取消订阅) ...， CLICK 等）
                switch ($message->Event) {
                    case 'subscribe':
                        return '欢迎关注https//:www.wc87.com';
                        break;

                    default:
                        # code...
                        break;
                }

                # 扫描带参数二维码事件
                $message->EventKey;    //事件KEY值，比如：qrscene_123123，qrscene_为前缀，后面为二维码的参数值
                $message->Ticket;      //二维码的 ticket，可用来换取二维码图片

                # 上报地理位置事件
                $message->Latitude;    //23.137466   地理位置纬度
                $message->Longitude;   //113.352425  地理位置经度
                $message->Precision;   //119.385040  地理位置精度

                # 自定义菜单事件
                $message->EventKey;    //事件KEY值，与自定义菜单接口中KEY值对应，如：CUSTOM_KEY_001, www . qq . com
            } elseif ($msgType == 'text') {# 文字消息...
                $content = $message->Content;  // 文本消息内容
                return '我们已经收到您的消息：“' . $content . '”';
            } elseif ($msgType == 'image') {# 图片消息...
                $message->PicUrl;   // 图片链接
            } elseif ($msgType == 'voice') {# 语音消息...

                $message->MediaId;      // 语音消息媒体id，可以调用多媒体文件下载接口拉取数据。
                $message->Format;          //语音格式，如 amr，speex 等
                $message->Recognition;  // * 开通语音识别后才有
                // > 请注意，开通语音识别后，用户每次发送语音给公众号时，微信会在推送的语音消息XML数据包中，增加一个 `Recongnition` 字段
            } elseif ($msgType == 'video') {# 视频消息...

                $message->MediaId;      // 视频消息媒体id，可以调用多媒体文件下载接口拉取数据。
                $message->ThumbMediaId; // 视频消息缩略图的媒体id，可以调用多媒体文件下载接口拉取数据。
            } elseif ($msgType == 'location') {# 坐标消息...

                $message->Location_X;  //地理位置纬度
                $message->Location_Y;  //地理位置经度
                $message->Scale;       //地图缩放大小
                $message->Label;       //地理位置信息
            } elseif ($msgType == 'link') {# 链接消息...

                $message->Title;        //消息标题
                $message->Description;  //消息描述
                $message->Url;          //消息链接
            } else {# 其他消息...

            }
            return $message->Content;
        });
        return $server->serve();
    }
}
