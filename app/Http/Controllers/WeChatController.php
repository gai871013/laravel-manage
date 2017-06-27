<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Car;
use App\Models\Expense;
use App\Models\Follower;
use App\Models\Journey;
use App\Models\Task;
use App\User;
use EasyWeChat\Foundation\Application;
use function EasyWeChat\Payment\get_client_ip;
use Illuminate\Http\Request;

class WeChatController extends Controller
{
    private $user;

    /**
     * 构造函数 2017-5-10 17:31:32 by gai871013
     * WeChatController constructor.
     */
    public function __construct()
    {
        $this->middleware('weChat.bindUser')
            ->except('getBindUser', 'postBindUser', 'getFollower');
    }


    /**
     * 微信端首页 2017-5-11 12:07:11 by gai871013
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex(Request $request)
    {
        $follower = $this->getFollower();
        $return = false;
        $journeys = Journey::where('user_id', $follower->user->id)->whereIn('status', [0, 1])->first();
        if (!empty($journeys)) {
            $return = true;
        }
        return view('wechat.index', compact('return'));
    }

    /**
     * 获取用户微信信息 2017-5-11 12:08:09 by gai871013
     * @return mixed
     */
    private function getFollower()
    {
        $follower = session('wechat.oauth_user');
        $follower = Follower::where('openid', $follower['id'])->first();
        return $follower;
    }


    /**
     * 绑定用户 2017-5-11 09:43:14 by gai871013
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getBindUser(Request $request)
    {
        $follower = $this->getFollower();
        if ($follower->user) {
            return redirect()->route('weChat.home');
        }
        return view('wechat.bindUser', compact('follower'));
    }

    /**
     * 绑定用户Action 2017-5-11 12:07:35 by gai871013
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postBindUser(Request $request)
    {
        $data = $request->all();
        $follower = $this->getFollower();
        $validator = \Validator::make($data, [
            'tel' => 'required|numeric',
            'uid' => 'required|numeric',
            'code' => 'required|numeric'
        ]);
        if ($validator->fails()) {
            $return = [
                'status_code' => 40023,
                'message' => trans('common.40023'),
                'data' => $validator->errors()->all()
            ];
            return $this->ajaxReturn($return);
        }
        // 查找用户
        $user = User::where('uid', $data['uid'])->where('mobile', $data['tel'])->lockForUpdate()->first();
        if (empty($user)) {
            return $this->json(trans('common.40018'), 40018);
        } else {
            // 将用户之前绑定的微信号解绑
            Follower::where('user_id', $user->id)->update(['user_id' => 0]);

            // 检测验证码是否正确
            $check = Helper::checkCode($data['tel'], $data['code']);
            if ($check['status_code'] != 20000) {
                return $this->ajaxReturn($check);
            }
            $user->follower_id = $follower->id;
            $user->save();
            Follower::where('id', $follower->id)->update(['user_id' => $user->id]);
        }
        return $this->json(trans('common.20002'), 20002);
    }

    /**
     * 我的任务页面 2017-5-11 12:09:35 by gai871013
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getTask(Request $request)
    {
//        $dis = Helper::routeMatrix(113.630228,34.829576,113.689013,34.745604)->getContents();
//        echo($dis);
//        die;
        $user = $this->getFollower()->user;
        $task = Task::where('user_id', $user->id)->orderBy('id', 'DESC')->paginate(env('PAGE_SIZE'));
        return view('wechat.task', compact('task'));
    }

    /**
     * 获取任务详情 2017-5-15 10:07:48 by gai871013
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function getTaskDetail(Request $request)
    {
        $data = $request->all();
        $id = isset($data['id']) ? $data['id'] : 0;
        $user = $this->getFollower()->user;
        $task = Task::where('user_id', $user->id)->orderBy('id', 'desc');
        if ($id > 0) {
            $task = $task->where('id', $id);
        }
        $task = $task->first();
        if (empty($task)) {
            return [];
        }
        $task->data = json_decode($task->data);
        $task->journey;
        $task->car;
        return $this->ajaxReturn($task);
    }

    /**
     * 完成任务 2017-5-15 16:30:44 by gai871013
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postFinishTask(Request $request)
    {
        $id = isset($request->all()['id']) ? $request->all()['id'] : 0;
        $user = $this->getFollower()->user;
        $task = Task::where('id', $id)->where('user_id', $user->id)->lockForUpdate()->first();
        if ($id == 0) {
            return response()->json(['status_code' => 400, 'message' => trans('common.400')]);
        } elseif (empty($task)) {
            return response()->json(['status_code' => 40041, 'message' => trans('common.40041')]);
        }
        $task->status = 1;
        $task->completed_at = date('Y-m-d H:i:s');
        $task->save();
        $un_finish = Task::where('status', 0)->where('user_id', $user->id)->count();
        if ($un_finish > 0) {
            $url = route('weChat.task');
        } else {
            $url = route('weChat.returnCar');
        }
        return response()->json(['status_code' => 20004, 'message' => trans('common.20004'), 'url' => $url]);

    }

    /**
     * 我的行程页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getJourneys(Request $request)
    {
        $user = $this->getFollower()->user;
        $journeys = Journey::where('user_id', $user->id)->orderBy('id', 'desc')->paginate(env('PAGE_SIZE'));
        return view('wechat.journeys', compact('journeys'));
    }

    /**
     * 申请用车 2017-5-11 14:28:28 by gai871013
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getApply(Request $request)
    {
        return view('wechat.apply');
    }

    /**
     * 归还车辆 2017-5-11 14:41:43 by gai871013
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getReturnCar(Request $request)
    {
        $user = $this->getFollower()->user;
        $journey = Journey::where('user_id', $user->id)->orderBy('id', 'desc')->first();
        $title = trans('index.returnTheCar');
        if (empty($journey)) {
            $message = '您还没有使用车辆';
            $link = [['url' => route('weChat.apply'), 'name' => trans('index.applyForCar')]];
            return view('wechat.message', compact('message', 'title', 'link'));
        } elseif ($journey->status == 0) {
            $message = '您的申请用车正在审核';
            $link = [['url' => route('weChat.journeys'), 'name' => trans('index.myJourney')], ['url' => route('weChat.task'), 'name' => trans('index.myTask')]];
            return view('wechat.message', compact('message', 'title', 'link'));
        } else {
            $task = Task::where('user_id', $user->id)->where('journey_id', $journey->id)->where('status', 0)->count();
            if ($task > 0) {
                $message = '您还有任务未完成，请完成后归还车辆！';
                $link = [['url' => route('weChat.task'), 'name' => trans('index.myTask')]];
                return view('wechat.message', compact('message', 'title', 'link'));
            }
        }
        $journey->data = json_decode($journey->data);
        return view('wechat.returnCar', compact('journey'));
    }

    /**
     * 申请行程Action 2017-5-11 18:05:01 by gai871013
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postApply(Request $request)
    {
        $data = $request->all();
        $follower = $this->getFollower();
        $user = $follower->user;
        $has = Journey::where('user_id', $user->id)->where('status', 0)->first();
        if (!empty($has)) {
            $return = [
                'status_code' => 40042,
                'message' => trans('common.40042')
            ];
            return $this->ajaxReturn($return);
        }
        $start_location = explode(',', $data['info']['start_location']);
        $end_location = explode(',', $data['info']['end_location']);
        $data['info']['data'] = (string)Helper::routeMatrix($start_location[0], $start_location[1], $end_location[0], $end_location[1]);
        // 新建行程
        $journey = new Journey();
        $journey->user_id = $user->id;
        $journey->cip = get_client_ip();
        $journey->start_point = '';
        $journey->end_point = '';
        $journey->save();
        $journey_id = $journey->id;

        Journey::where('id', $journey_id)->update($data['info']);
        // 新建任务
        $task = new Task();
        $task->user_id = $user->id;
        $task->journey_id = $journey_id;
        $task->name = $user->name;
        $task->mobile = $user->mobile;
        $task->company_id = $user->company_id;
        $task->company_name = $user->company_name;
        $task->use_time = $data['info']['use_time'];
        $task->content = $data['info']['content'];
        $task->cip = get_client_ip();
        $task->save();

        $return = [
            'status_code' => 20012,
            'message' => trans('common.20012')
        ];
        return $this->ajaxReturn($return);
    }

    /**
     * 获取行程详情 2017-5-12 14:47:09 by gai871013
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getJourneyDetail(Request $request)
    {
        $data = $request->all();
        $id = isset($data['id']) ? $data['id'] : 0;
        $user = $this->getFollower()->user;
        $journey = Journey::where('user_id', $user->id)->orderBy('id', 'desc');
        if ($id > 0) {
            $journey = $journey->where('id', $id);
        }
        $journey = $journey->first();
        if (empty($journey)) {
            return null;
        }
        $journey->data = json_decode($journey->data);
        $journey->expenses;
//        dd($journey);
        return $this->ajaxReturn($journey);
    }

    /**
     * 归还车辆Action 2017-5-15 16:32:26 by gai871013
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postReturnCar(Request $request)
    {
        $data = $request->all();
        $user = $this->getFollower()->user;
        $journey = Journey::where('user_id', $user->id)->orderBy('id', 'desc')->where('status', 1)->lockForUpdate()->first();
        if (!isset($data['expenses']) || count($data['expenses']) < 5) {
            return response()->json(['status_code' => 400, 'message' => trans('common.400')]);
        } elseif (empty($journey)) {
            return response()->json(['status_code' => 40041, 'message' => trans('common.40041')]);
        }
        $journey->status = 2;
        $journey->save();
        // 添加费用信息
        $insert = [];
        $date = date('Y-m-d H:i:s');
        $base = [
            'car_id' => $journey->car_id,
            'user_id' => $user->id,
            'journey_id' => $journey->id,
            'time' => $date,
            'cip' => get_client_ip(),
            'created_at' => $date,
            'updated_at' => $date
        ];
        foreach ($data['expenses'] as $k => $v) {
            $v = empty($v) ? 0 : (int)$v;
            $insert[] = array_merge($base, ['fee' => $v, 'paid_fee' => $v, 'type' => $k]);
        }
        Expense::insert($insert);
        // 车辆状态归0
        Car::where('id', $journey->car_id)->update(['status' => 0]);
        return response()->json(['status_code' => 20004, 'message' => trans('common.20004')]);

    }
}
