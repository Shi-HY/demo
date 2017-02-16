<?php
namespace Admin\Controller;

use Admin\Common\common\BaseController;
use Admin\Common\common\Prompt;

class ClerkController extends BaseController {
    public function index(){
        //门店Id
        $store_id = I('get.store_id');

        $res = M('admin')->where(array('store_id'=>$store_id))->select();
        //门店名称
        $store_name = M('store')->field('store_name')->find($store_id);

        $data=array();
        for($i=0; $i<count($res); $i++)
        {
            $data[$i]['uid'] = $res[$i]['uid'];
            $data[$i]['username'] = $res[$i]['username'];
            $data[$i]['password'] = $res[$i]['password'];
            $data[$i]['is_active'] =  $res[$i]['is_active'];
            $data[$i]['sex'] =  $res[$i]['sex'];
            $data[$i]['create_time'] = date('Y-m-d H:i',$res[$i]['create_time']);
            $data[$i]['update_time'] = date('Y-m-d H:i',$res[$i]['update_time']);
            $data[$i]['store_name'] = $res['store_name'];
        }

        $this->assign('data', $data);
        $this->assign('store_name', $store_name);
        $this->assign('store_id', $store_id);
    	$this->display();
    }

    public function add()
    {
        if(IS_GET)
        {
            /**
             * 输出选择职位
             */
           /* $auth_group = M('auth_group')->where('status=1')->order('id ASC')->select();
            $this->assign('role', $auth_group);*/

            $store_id = I('get.store_id');
            $this->assign('store_id', $store_id);
            $this->display();
        }else{
            /**
             * 添加操作
             */
            $admin = M('admin');
            $data = I('post.');

            $map['username'] = array('eq',$data['username']);
            $msg = $admin->where($map)->select();
            if($msg)
            {
                $response = array(
                    'status' => 'existence',
                    'title' => Prompt::$AdminPrompt['existence'],
                );
                return $this->ajaxReturn($response);
            }

            $act['store_id'] = $data['store_id'];
            $act['username'] = trim($data['username']);
            $act['password'] = trim(md5($data['password']));
            $act['is_active'] = $data['is_active'];
            $act['sex'] = $data['sex'];
            $act['create_time'] = $_SERVER['REQUEST_TIME'];
            $act['update_time'] = $_SERVER['REQUEST_TIME'];

            $result = $admin->add($act);
            if($result){
                //$auth_group_access['group_id']=$data['role_id'];
                $auth_group_access['group_id']=3;       //默认是 3  店员
                $auth_group_access['uid']=$result;

                $auth_group_access_res=M('auth_group_access')->add($auth_group_access);

                if($auth_group_access_res)
                {
                    $response = array(
                        'status' => 'a_success',
                        'title' => Prompt::$ClerkPrompt['a_success'],
                    );
                }else{
                    $response = array(
                        'status' => 'a_err',
                        'title' => Prompt::$ClerkPrompt['a_err'],
                    );
                }

            }else{
                $response = array(
                    'status' => 'a_err',
                    'title' => Prompt::$ClerkPrompt['a_err'],
                );
            }
            return $this->ajaxReturn($response);
        }
    }

    public function edit()
    {
        if(IS_GET)
        {
            $uid = I('get.uid');
            $store_id = I('get.store_id');
            $res = M('admin')->find($uid);

            if (empty($res)) {
                $this->redirect('index');
                exit;
            }

            $this->assign('data', $res);
            $this->assign('store_id', $store_id);
            $this->display();
        }else{
            /**
             * 修改操作
             */
            $admin = M('admin');
            $data = I('post.');

            $map['username'] = array('eq',$data['username']);
            $uid['uid'] = array('eq',$data['uid']);

            $msg = $admin->where($map)->select();

            if($msg)
            {
                $response = array(
                    'status' => 'existence',
                    'title' => Prompt::$AdminPrompt['existence'],
                );
                return $this->ajaxReturn($response);
            }

            $admin->username = trim($data['username']);
            $admin->password = trim(md5($data['password']));
            $admin->is_active = $data['is_active'];
            $admin->sex = $data['sex'];
            $admin->update_time = $_SERVER['REQUEST_TIME'];

            $result = $admin->where($uid)->save();

            if($result){
                /**
                 * 如果修改成功,在修改职位
                 */
                $auth_group_access = M('auth_group_access');
                //$auth_group_access->group_id=$data['role_id'];
                $auth_group_access->group_id=3;             //默认是 3  店员

                $auth_group_access_res=$auth_group_access->where($uid)->save();

                if($auth_group_access_res)
                {
                    $response = array(
                        'status' => 'u_success',
                        'title' => Prompt::$ClerkPrompt['u_success'],
                    );

                }else{
                    $response = array(
                        'status' => 'u_err',
                        'title' => Prompt::$ClerkPrompt['u_err'],
                    );
                }
            }else{
                $response = array(
                    'status' => 'u_err',
                    'title' => Prompt::$ClerkPrompt['u_err'],
                );
            }

            return $this->ajaxReturn($response);
        }
    }

    public function del()
    {
        $data['uid'] = array('eq',I('get.uid'));
        $data['store_id'] = array('eq',I('get.store_id'));

        $back_now = M('back_now')->where(array('uid'=>$data['uid']))->select();

        if($back_now)
        {
            $response = array(
                'status' => 'record',
                'title' => Prompt::$ClerkPrompt['record'],
            );
        }else{
            if (M('admin')->where($data)->delete()) {
                $response = array(
                    'status' => 'd_success',
                    'title' => Prompt::$ClerkPrompt['d_success'],
                );
            } else {
                $response = array(
                    'status' => 'd_err',
                    'title' => Prompt::$ClerkPrompt['d_err'],
                );
            }
        }
        return $this->ajaxReturn($response);
    }

    public function lock() {
        //门店Id
        $store_id = $_GET['store_id'];
        if(!empty($_GET['uid'])) {
            $data['uid'] = $_GET['uid'];

            $data['is_active'] = 0;
            M('admin')->save($data);
            $this->redirect('index',array('store_id'=>$store_id));
        } else {
            $this->redirect('index',array('store_id'=>$store_id));
        }
    }

    public function active() {
        //门店Id
        $store_id = $_GET['store_id'];
        if(!empty($_GET['uid'])) {
            $data['uid'] = $_GET['uid'];
            $data['is_active'] = 1;

            M('admin')->save($data);
            $this->redirect('index',array('store_id'=>$store_id));
        } else {
            $this->redirect('index',array('store_id'=>$store_id));
        }
    }
}