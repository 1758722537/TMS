<?php
namespace app\index\controller;
use app\admin\model\User;
use app\index\common\Base;
use app\index\model\UserInfo;
use app\index\model\Record;
use think\Db;
use think\Loader;
use think\Request;
use think\Session;

class Index extends Base
{
    /**
     * 首页
     * @return mixed
     */
    public function index()
    {
        $imageList = Db::table('carousel')->where(['status'=>1])->select();
        $videoList = Db::table('video')->where(['status'=>1])->paginate(4);
        $this->assign(['imageList'=>$imageList,'videoList'=>$videoList]);
    	return $this->fetch();
    }

    /**
     * 注册页面
     * @return mixed
     */
    public function register()
    {
        return $this->fetch();
    }
    /**
     * 检查用户名是否可用
     * @param Request $request
     * @return array
     */
    public function checkUserName(Request $request)
    {
        //默认用户名可用
        $status = 1;
        $message = '用户名可用';
        $name = trim($request->param('name'));
        if(UserInfo::get(['name'=>$name])){
            $status = 0;
            $message = '用户名不可用，请重新输入';
        }
        return ['status'=>$status,'message'=>$message];
    }

    /**
     * 处理注册
     * @param Request $request
     * @return array
     */
    public function doregister(Request $request)
    {
        $status = 1;
        $message = "注册成功！";
        $data = $request->param();
        $validate = Loader::validate('UserInfo');
        if (!$validate->scene('register')->check($data)){
            $status = 0;
            $message = $validate->getError();
        }else{
            $res = Userinfo::create($data);
            if (!$res){
                $status = 0;
                $message = "注册失败，请重新注册";
            }
        }
        return ['status'=>$status,'message'=>$message];

    }

    /**
     * 渲染登录页面
     * @return mixed
     */
    public function login()
    {
        $this->alreadyLogin();
        return $this->fetch();
    }

    /**
     * 验证登录
     * @param Request $request
     * @return array
     */
    public function doLogin(Request $request)
    {
        $data = $request->param();
        $validate = Loader::validate('UserInfo');
        $result = $validate->scene('login')->check($data);
        if(!$result){
            $status = 0;
            $message = $validate->getError();
        }else{
            $map = [
                'name' => $data['name'],
                'password' => md5($data['password'])
            ];
            $user = UserInfo::get($map);
            if(!$user){
                $status = 0;
                $message = "用户名或密码错误";
            }else{
                $status = 1;
                $message = "登陆成功！";
                Session::set('user_sid',$user->getData('id'));
                Session::set('user_sinfo',$user->getData());
            }
        }
        return [
            'status' => $status,
            'message'=>$message
        ];
    }

    /**
     * 个人信息
     * @param Request $request
     * @return mixed
     */
    public function userInfo(Request $request)
    {
        $id = $request->param('id');
        $list = Userinfo::get($id);
        //dump($list);
        $this->assign(['info'=>$list]);
        return $this->fetch();
    }
    public function infoEdit(Request $request)
    {
        $id = $request->param('id');
        $data = $request->param();
        $user = Userinfo::update($data,['id'=>$id]);
        if($user){
            $this->success();
        }
    }
    /**
     * 退出登录
     */
    public function logout()
    {
        //注销Session
        Session::delete('user_sid');
        Session::delete('user_sinfo');
        $this->success('注销成功','index/index');
    }
    /**
     * 测试
     */
    public function test()
    {
        $id = Session::get('user_sinfo');
        if(!$id){
            echo "null";
        }else{
            dump($id);
        }
    }
}
