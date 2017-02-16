<?php
namespace Admin\Controller;
use Admin\Common\common\BaseController;
use Admin\Common\common\Prompt;

class RuleController extends BaseController {
    public function index(){
        $auth_rule = M('auth_rule');
        $data = $auth_rule->select();

        $arr=array();
        for($i=0; $i<count($data); $i++)
        {
            $arr[$i]['id'] = $data[$i]['id'];
            $arr[$i]['name'] = $data[$i]['name'];
            $arr[$i]['title'] = $data[$i]['title'];
            $arr[$i]['sort'] = $data[$i]['sort'];
            $arr[$i]['p_a'] = $data[$i]['p_a'];
            $arr[$i]['remark'] = $data[$i]['remark'];
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
            $category = M('auth_rule')->order('pid ASC')->select();
            $this->assign('category', $category);
            $this->display();
        }else{
            /**
             * 执行添加操作
             */
            $auth_rule = M('auth_rule');
            $data = I('post.');

            $act['name'] = trim($data['name']);
            $act['title'] = trim($data['title']);
            $act['sort'] = trim($data['sort']);
            $act['p_a'] = trim($data['p_a']);
            $act['p_c'] = trim($data['p_c']);
            $act['level'] = trim($data['level']);
            $act['remark'] = trim($data['remark']);
            $act['pid'] = $data['pid'];
            $act['create_time'] = $_SERVER['REQUEST_TIME'];
            $act['update_time'] = $_SERVER['REQUEST_TIME'];

            $result = $auth_rule->add($act);

            if($result){

                $auth_rule->p_path = $act['pid'].','.$result;

                $res = $auth_rule->where(array('id'=>$result))->save();

                if($res)
                {
                    $response = array(
                        'status' => 'a_success',
                        'title' => Prompt::$AuthRulePrompt['a_success'],
                    );
                }else{
                    $response = array(
                        'status' => 'a_err',
                        'title' => Prompt::$AuthRulePrompt['a_err'],
                    );
                }
            }else{
                $response = array(
                    'status' => 'a_err',
                    'title' => Prompt::$AuthRulePrompt['a_err'],
                );
            }
            return $this->ajaxReturn($response);
        }
    }

    public function view()
    {
        $id = I('get.id');
        $data = M('auth_rule')->find($id);
        $p_data = M('auth_rule')->where(array('id'=>$data['pid']))->find();
        $p_name = $p_data['title'] == '' ?'PRIMARY CLASSIFCATION':$p_data['title'];

        if (empty($data)) {
            $this->redirect('index');
            exit;
        }
        $data['create_time'] = date('Y-m-d H:i',$data['create_time']);
        $data['update_time'] = date('Y-m-d H:i',$data['update_time']);

        $this->assign('data', $data);
        $this->assign('p_name', $p_name);   //父类名称
        $this->display();
    }

    public function edit()
    {
        if(IS_GET)
        {
            $id = I('get.id');
            $data = M('auth_rule')->find($id);
            $category = M('auth_rule')->order('pid ASC')->select();
            if (empty($data)) {
                $this->redirect('index');
                exit;
            }

            $this->assign('data', $data);
            $this->assign('category', $category);
            $this->display();
        }else{
            /**
             * 执行修改操作
             */
            $auth_rule = M('auth_rule');
            $data = I('post.');
            $id['id'] = array('eq',$data['id']);

            $auth_rule->name = trim($data['name']);
            $auth_rule->title = trim($data['title']);
            $auth_rule->sort = trim($data['sort']);
            $auth_rule->p_a = trim($data['p_a']);
            $auth_rule->p_c = trim($data['p_c']);
            $auth_rule->level = trim($data['level']);
            $auth_rule->remark = trim($data['remark']);
            $auth_rule->pid = $data['pid'];
            $auth_rule->update_time = $_SERVER['REQUEST_TIME'];
            $auth_rule->p_path = $data['pid'].','.$data['id'];

            $result = $auth_rule->where($id)->save();

            if($result){
                $response = array(
                    'status' => 'u_success',
                    'title' => Prompt::$AuthRulePrompt['u_success'],
                );
            }else{
                $response = array(
                    'status' => 'u_err',
                    'title' => Prompt::$AuthRulePrompt['u_err'],
                );
            }
            return $this->ajaxReturn($response);
        }
    }
    
    public function del()
    {
        if (empty($_GET['id'])) {
            $this->redirect("index");
            exit;
        }

        $id = I('get.id');

        if (M('auth_rule')->delete($id)) {
            $response = array(
                'status' => 'd_success',
                'title' => Prompt::$AuthRulePrompt['d_success'],
            );
        } else {
            $response = array(
                'status' => 'd_err',
                'title' => Prompt::$AuthRulePrompt['d_err'],
            );
        }
        return $this->ajaxReturn($response);
    }

}