<?php

namespace App\Http\Controllers\Api;

use App\Entities\FormIds;
use App\Http\Controllers\Controller;
use App\Repositories\CardsRepositoryEloquent;
use App\Repositories\FollowersRepositoryEloquent;
use App\Repositories\MiniProgramTokensRepositoryEloquent;
use App\Repositories\UsersRepositoryEloquent;
use EasyWeChat\MiniProgram\Application;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class MiniProgramController extends Controller
{

    protected $token;
    protected $follower;
    protected $user;
    protected $card;

    public function __construct(
        MiniProgramTokensRepositoryEloquent $miniProgramTokensRepositoryEloquent,
        FollowersRepositoryEloquent $followersRepositoryEloquent,
        UsersRepositoryEloquent $usersRepositoryEloquent,
        CardsRepositoryEloquent $cardsRepositoryEloquent
    )
    {
        $this->token = $miniProgramTokensRepositoryEloquent;
        $this->follower = $followersRepositoryEloquent;
        $this->user = $usersRepositoryEloquent;
        $this->card = $cardsRepositoryEloquent;

        $this->middleware('MiniProgram.api')->except(['getToken', 'test']);
    }

    /**
     * 获取用户信息 2017-9-12 17:30:09 by gai871013
     * @param Request $request
     * @param Application $application
     * @return array|\EasyWeChat\Kernel\Support\Collection|\Illuminate\Http\JsonResponse|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function getToken(Request $request, Application $application)
    {
        $code = $request->input('code', '');
        $res = $application->auth->session($code);
        if (isset($res['errcode'])) {
            return $res;
        }
        $where = ['openid' => $res['openid']];
        $follower = $this->follower->updateOrCreate($where,
            array_merge($where, ['app_id' => 1, 'created_at' => date('Y-m-d H:i:s')]));
        $id = Uuid::uuid4();
        $update = [
            'token'       => $id,
            'follower_id' => $follower->id,
            'expires_at'  => date('Y-m-d H:i:s', (time() + 86400 * 365)),
        ];
        $token = $this->token->updateOrCreate($where, $update);
        $user = $token->user;
        if (empty($user)) {
            $name = md5($res['openid']);
            $create = [
                'follower_id' => $follower->id,
                'username'    => $name,
                'password'    => password_hash($name, PASSWORD_DEFAULT),
            ];
            $res = $this->user->create($create);
            $this->token->update(['user_id' => $res->id], $token['id']);
        }
        $token = $this->token->with('user')->findWhere($where)->first();
        return $this->ajaxReturn(['status_code' => 200, 'message' => __('common.20003'), 'data' => $token]);
    }


    /**
     * 保存FormId
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveFormIds()
    {
        $token = request('token');
        $formIds = request('formIds');
        $formIds = json_decode($formIds, true) ?? [];
        if ($formIds) {
            $insert = [];
            $time = date('Y-m-d H:i:s');
            foreach ($formIds as $v) {
                if (!isset($v['formId']) || $v['formId'] == 'the formId is a mock one') {
                    continue;
                }
                $insert[] = [
                    'follower_id' => $token['follower_id'],
                    'user_id'     => $token['user_id'],
                    'openid'      => $token['openid'],
                    'formId'      => $v['formId'],
                    'expire_at'   => date("Y-m-d H:i:s", time() + 561600),
                    'created_at'  => $time,
                    'updated_at'  => $time,
                ];
            }
            FormIds::insert($insert);
        }
        $return = [
            'status_code' => 200,
            'message'     => '成功',
        ];
        return $this->ajaxReturn($return);
    }

    /**
     * 提交个人姓名&&手机号
     */
    public function saveUserDetail()
    {
        $token = request('token');
        $data = request('info');
        $data = json_decode($data, true);
        $user = $this->user->update($data, $token['user_id']);
        $return = [
            'status_code' => 20002,
            'message'     => '保存成功',
            'data'        => $user
        ];
        return $this->ajaxReturn($return);
    }

    /**
     * 保存用户的头像和昵称
     */
    public function saveUserInfo()
    {
        $token = request('token');
        $data = request('info');
        $data = json_decode($data, true);
        $this->follower->update($data, $token['follower_id']);
        $this->user->update(['nickName' => $data['nickName'], 'sex' => $data['gender'], 'avatarUrl' => $data['avatarUrl']], $token['user_id']);
        $return = [
            'status_code' => 20002,
            'message'     => '保存成功',
        ];
        return $this->ajaxReturn($return);
    }

    /**
     * 获取好友列表 2018-09-06 23:19:00 by gai871013
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function getFriendLists()
    {
        $page = request('page', 1);
        $token = request('token');
        $keyword = request('keyword');
        $lists = $this->card->makeModel()->whereHas('detail', function ($query) use ($keyword) {
            $query->where('name', 'like', '%' . $keyword . '%')->orWhere('company_name', 'like', '%' . $keyword . '%')->orWhere('position', 'like', '%' . $keyword . '%');
        }
        )->with('detail')->where(['user_id' => $token['user_id']])->orderBy('id', 'desc')->take(15)->skip(($page - 1) * 15)->get();
        return $this->ajaxReturn(['status_code' => 20002, 'message' => '获取成功', 'data' => $lists]);
    }

    /**
     * 获取用户信息 by gai871013
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function getUserInfo()
    {
        $token = request('token');
        $id = request('id', $token['user_id']);
        $user = $this->user->findByField('id', $id)->first();
        $is_friend = $this->card->findWhere(['user_id' => $token['user_id'], 'friend_id' => $id])->first();
        $user['is_friend'] = $is_friend ?? false;
        $this->user->makeModel()->where(['id' => $id])->increment('read');
        return $this->ajaxReturn(['status_code' => 20002, 'message' => '获取成功', 'data' => $user]);
    }


    /**
     * 操作好友关系
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function getCard()
    {
        $type = request('type');
        $token = request('token');
        $id = request('id');
        if ($type == 'add') {
            $this->card->create(['user_id' => $token['user_id'], 'friend_id' => $id, 'date' => date('Y-m-d')]);
            $this->user->makeModel()->where('id', $id)->increment('enshrine');
            $message = '收藏成功 😊';
        } elseif ($type == 'cancel') {
            $this->card->deleteWhere(['user_id' => $token['user_id'], 'friend_id' => $id]);
            $this->user->makeModel()->where('id', $id)->decrement('enshrine');
            $message = '取消收藏成功 😔';
        }

        return $this->ajaxReturn(['status_code' => 20002, 'message' => $message]);
    }
}
