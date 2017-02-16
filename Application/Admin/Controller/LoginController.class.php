<?php
namespace Admin\Controller;
use Think\Controller;
use Admin\Common\common\Prompt;

class LoginController extends Controller {

    public function login(){
    	if(IS_POST)
        {
            $name=trim(I('post.username'));
            $password=trim(I('post.password'));

            //验证
            $admin=M('admin');
            $data=$admin->field('uid,username,password,is_active,store_id')->where(array('username'=>$name))->find();
            
            if(!$data){
                $response = array(
                    'status' => 'user_null',
                    'title' => Prompt::$LoginPrompt['user_null'],
                );
                return $this->ajaxReturn($response);
            }
            //验证密码
            if($data['password'] != md5($password)){
                $response = array(
                    'status' => 'pwd_err',
                    'title' => Prompt::$LoginPrompt['pwd_err'],
                );
                return $this->ajaxReturn($response);
            }

            //验证状态
            if($data['is_active']== '0'){
                $response = array(
                    'status' => 'disable',
                    'title' => Prompt::$LoginPrompt['disable'],
                );
                return $this->ajaxReturn($response);
            }

            //把用户信息加入到session
            $_SESSION['Admin']=$data;
            unset($_SESSION['Admin']['is_active']);
            unset($_SESSION['Admin']['password']);

            $response = array(
                'status' => 'success',
                'title' => Prompt::$LoginPrompt['success'],
            );
            return $this->ajaxReturn($response);
        }else{
            $this->display();
        }
    }

    public function logout() {
        unset($_SESSION['Admin']);
        $this -> redirect('login');
    }
}