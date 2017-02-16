<?php
namespace Admin\Controller;
use Admin\Common\common\BaseController;
use Admin\Common\common\Prompt;
use Admin\Controller\LabelCode;

class RoleController extends BaseController {
    public function index(){
        $auth_group = M('auth_group');
        $data = $auth_group->select();

        $arr=array();
        for($i=0; $i<count($data); $i++)
        {
            $arr[$i]['id'] = $data[$i]['id'];
            $arr[$i]['title'] = $data[$i]['title'];
            $arr[$i]['status'] =  $data[$i]['status'];
            $arr[$i]['create_time'] = date('Y-m-d H:i',$data[$i]['create_time']);
            $arr[$i]['update_time'] = date('Y-m-d H:i',$data[$i]['update_time']);
        }

        $this->assign('data', $arr);
        $this->display();
    }

    public function add()
    {
        if(IS_GET)
        {
            $this->display();
        }else{
            $auth_group = M('auth_group');
            $data = I('post.');

            $act['title'] = trim($data['title']);
            $act['status'] = $data['status'];
            $act['create_time'] = $_SERVER['REQUEST_TIME'];
            $act['update_time'] = $_SERVER['REQUEST_TIME'];

            $result = $auth_group->add($act);

            if($result){
                $response = array(
                    'status' => 'a_success',
                    'title' => Prompt::$AuthGroupPrompt['a_success'],
                );
            }else{
                $response = array(
                    'status' => 'a_err',
                    'title' => Prompt::$AuthGroupPrompt['a_err'],
                );
            }
            return $this->ajaxReturn($response);
        }
    }

    public function edit()
    {
        if(IS_GET)
        {
            $id = I('get.id');
            $data = M('auth_group')->find($id);

            if (empty($data)) {
                $this->redirect('index');
                exit;
            }

            $this->assign('data', $data);
            $this->display();
        }else{
            $auth_group = M('auth_group');
            $data = I('post.');
            $id['id'] = array('eq',$data['id']);

            $auth_group->title = trim($data['title']);
            $auth_group->status = $data['status'];
            $auth_group->update_time = $_SERVER['REQUEST_TIME'];

            $result = $auth_group->where($id)->save();

            if($result){
                $response = array(
                    'status' => 'u_success',
                    'title' => Prompt::$AuthGroupPrompt['u_success'],
                );
            }else{
                $response = array(
                    'status' => 'u_err',
                    'title' => Prompt::$AuthGroupPrompt['u_err'],
                );
            }
            return $this->ajaxReturn($response);
        }
    }

    public function lock() {
        if(!empty($_GET['id'])) {
            $data['id'] = $_GET['id'];
            $data['status'] = 0;
            M('auth_group')->save($data);
            $this->redirect('index');
        } else {
            $this->redirect('index');
        }
    }

    public function active() {
        if(!empty($_GET['id'])) {
            $data['id'] = $_GET['id'];
            $data['status'] = 1;
            M('auth_group')->save($data);
            $this->redirect('index');
        } else {
            $this->redirect('index');
        }
    }

    /**
     * 修改权限
     */
    public function authorization()
    {
        if(IS_GET)
        {
            $urls=M('auth_rule')->where('level in(1)')->select();
            foreach($urls as $key=>$val){
                $urls[$key]['title']= LabelCode::getlbText($val['title']);
                $res=M('auth_rule')->where('pid='.$val['id'])->select();
                $urls[$key]['two']=$res;
            }

            foreach($urls as $key=>$val){
               foreach($val['two'] as $k=>$v){
                   $urls[$key]['two'][$k]['title']= LabelCode::getlbText($v['title']);
               }
            }
            $result = M('auth_group')->find(I('get.id'));

            $this->assign('myurls',$result['rules']);

            $this->assign('data', $urls);
            $this->assign('role_id',$_GET['id']);
            $this->display();
        }else{
            $auth_group = M('auth_group');
            $data = I('post.');
            $id['id'] = array('eq',$data['role_id']);

            if(empty($data['Ids'])) {
                $response = array(
                    'status' => 'authorization_err',
                    'title' => Prompt::$AuthGroupPrompt['authorization_err'],
                );
                return $this->ajaxReturn($response);
            }

            $auth_group->rules = $data['Ids'];
            $auth_group->update_time = $_SERVER['REQUEST_TIME'];

            $result = $auth_group->where($id)->save();

            if($result) {
                $response = array(
                    'status' => 'authorization_success',
                    'title' => Prompt::$AuthGroupPrompt['authorization_success'],
                );
            }else{
                $response = array(
                    'status' => 'a_err',
                    'title' => Prompt::$AuthGroupPrompt['a_err'],
                );
            }
            return $this->ajaxReturn($response);
        }
    }
}