<?php
namespace Admin\Controller;

use Admin\Common\common\BaseController;
use Admin\Common\common\Prompt;

class AdminController extends BaseController {
    public function index(){
        $admin = M('admin');

        $data = $admin->where('store_id = 0')->select();

        $admin=array();
        for($i=0; $i<count($data); $i++)
        {
            $admin[$i]['uid'] = $data[$i]['uid'];
            $admin[$i]['username'] = $data[$i]['username'];
            $admin[$i]['password'] = $data[$i]['password'];
            $admin[$i]['is_active'] =  $data[$i]['is_active'];
            $admin[$i]['sex'] =  $data[$i]['sex'];
            $admin[$i]['create_time'] = date('Y-m-d H:i',$data[$i]['create_time']);
            $admin[$i]['update_time'] = date('Y-m-d H:i',$data[$i]['update_time']);

            //职位
            $role=M('auth_group_access')->alias('ru')
                                ->join('left join p_auth_group as r ON ru.group_id=r.id')
                                ->field('r.title')
                                ->where(array('ru.uid'=>$data[$i]['uid']))
                                ->find();
            //var_dump($role);
            $admin[$i]['role'] =  $role['title'];
        }

        $this->assign('data', $admin);
    	$this->display();
    }

    public function add()
    {
        if(IS_GET)
        {
            /**
             * 输出选择职位
             */
            $auth_group = M('auth_group')->where('status=1 && id!=3')->order('id ASC')->select();
            $this->assign('role', $auth_group);
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

            $act['username'] = trim($data['username']);
            $act['password'] = trim(md5($data['password']));
            $act['is_active'] = $data['is_active'];
            $act['store_id'] = 0;
            $act['sex'] = $data['sex'];
            $act['create_time'] = $_SERVER['REQUEST_TIME'];
            $act['update_time'] = $_SERVER['REQUEST_TIME'];

            $result = $admin->add($act);
            if($result){

                $auth_group_access['group_id']=$data['role_id'];
                $auth_group_access['uid']=$result;

                $auth_group_access_res=M('auth_group_access')->add($auth_group_access);

                if($auth_group_access_res)
                {
                    $response = array(
                        'status' => 'a_success',
                        'title' => Prompt::$AdminPrompt['a_success'],
                    );
                }else{
                    $response = array(
                        'status' => 'a_err',
                        'title' => Prompt::$AdminPrompt['a_err'],
                    );
                }

            }else{
                $response = array(
                    'status' => 'a_err',
                    'title' => Prompt::$AdminPrompt['a_err'],
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
            $data = M('admin')->find($uid);

            if (empty($data)) {
                $this->redirect('index');
                exit;
            }

            /**
             * 职位
             */
            $role=M('auth_group_access')->alias('ga')
                ->join('left join p_auth_group as g ON ga.group_id=g.id')
                ->field('g.title,g.id')
                ->where(array('ga.uid'=>$uid))
                ->find();

            $data['role'] = $role['title'];
            $data['roleId'] = $role['id'];

            $roles = M('auth_group')->where('status=1')->order('id ASC')->select();

            $this->assign('roles', $roles);
            $this->assign('data', $data);

            $this->display();
        }else{

            /**
             * 修改操作
             */
            $admin = M('admin');
            $data = I('post.');

            $map['username'] = array('eq',$data['username']);
            $uid['uid'] = array('eq',$data['uid']);

            //session id
            $session_uid=$_SESSION['Admin']['uid'];

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
                $auth_group_access->group_id=$data['role_id'];
                $auth_group_access->update_time=$_SERVER['REQUEST_TIME'];

                $auth_group_access_res=$auth_group_access->where($uid)->save();

                if($auth_group_access_res)
                {
                    /**
                     * 如果和session一样,就重新登录
                     */
                    if($data['uid'] == $session_uid){
                        $response = array(
                            'status' => 'u_success',
                            'again_login'  => 'yes',
                            'title'  => Prompt::$AdminPrompt['u_again'],
                        );
                    }else{
                        $response = array(
                            'status' => 'u_success',
                            'title' => Prompt::$AdminPrompt['u_success'],
                        );
                    }

                }else{
                    $response = array(
                        'status' => 'u_err',
                        'title' => Prompt::$AdminPrompt['u_err'],
                    );
                }
            }else{
                $response = array(
                    'status' => 'u_err',
                    'title' => Prompt::$AdminPrompt['u_err'],
                );
            }

            return $this->ajaxReturn($response);
        }
    }

    public function del()
    {
        if (empty($_GET['uid'])) {
            $this->redirect("index");
            exit;
        }

        $uid = I('get.uid');
        $session_uid=$_SESSION['Admin']['uid'];

        if($uid == $session_uid){
            $response = array(
                'status' => 'd_this',
                'title'  => Prompt::$AdminPrompt['d_this'],
            );
        }else{
            $a = M('admin')->delete($uid);                                         //删除admin
            $aga = M('auth_group_access')->where(array('uid'=>$uid))->delete();    //删除相对应的 用户角色数据

            if($a && $aga)
            {
                $response = array(
                    'status' => 'd_success',
                    'title' => Prompt::$AdminPrompt['d_success'],
                );
            }else{
                $response = array(
                    'status' => 'd_err',
                    'title' => Prompt::$AdminPrompt['d_err'],
                );
            }
        }

        return $this->ajaxReturn($response);
    }

    public function lock() {
        if(!empty($_GET['uid'])) {
            $data['uid'] = $_GET['uid'];
            $data['is_active'] = 0;
            M('admin')->save($data);
            $this->redirect('index');
        } else {
            $this->redirect('index');
        }
    }

    public function active() {
        if(!empty($_GET['uid'])) {
            $data['uid'] = $_GET['uid'];
            $data['is_active'] = 1;
            M('admin')->save($data);
            $this->redirect('index');
        } else {
            $this->redirect('index');
        }
    }

    public function center()
    {
        $uid=$_SESSION['Admin']['uid'];
        $data = M('admin')->table('pn_admin a')
                          ->field(array('ag.title'=>'role','aga.group_id'=>'group_id','a.*'))
                          ->join('`p_auth_group_access` aga on aga.uid=a.uid')
                          ->join('`p_auth_group` ag on ag.id=aga.group_id')
                          ->where(array('a.uid'=>$uid))
                          ->find();
        $data['sex'] = $data['sex'] =='1' ?MAN:WOMAN;
        $data['is_active'] = $data['is_active'] =='1' ?ENABLE:DISABLE;

        if (empty($data)) {
            $this->redirect('index');
            exit;
        }
        $this->assign('data', $data);
        $this->display();
    }
}