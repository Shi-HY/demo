<?php
namespace Admin\Controller;

use Admin\Common\common\BaseController;
use Admin\Common\common\Prompt;

class StoreController extends BaseController {
    public function index(){
        $res =  M('store')->select();
        $data=array();
        for($i=0; $i<count($res); $i++)
        {
            $data[$i]['store_id'] = $res[$i]['store_id'];
            $data[$i]['store_name'] = $res[$i]['store_name'];
            $data[$i]['create_time'] = date('Y-m-d H:i',$res[$i]['create_time']);
            $data[$i]['update_time'] = date('Y-m-d H:i',$res[$i]['update_time']);
        }

        $this->assign('data', $data);
    	$this->display();
    }

    public function add()
    {
        if(IS_GET)
        {
            $this->display();
        }else{
            /**
             * 添加操作
             */
            $store = M('store');
            $data = I('post.');

            $act['store_name'] = trim($data['store_name']);
            $act['create_time'] = $_SERVER['REQUEST_TIME'];
            $act['update_time'] = $_SERVER['REQUEST_TIME'];

            $result = $store->add($act);
            if($result){
                $response = array(
                    'status' => 'a_success',
                    'title' => Prompt::$StorePrompt['a_success'],
                );
            }else{
                $response = array(
                    'status' => 'a_err',
                    'title' => Prompt::$StorePrompt['a_err'],
                );
            }
            return $this->ajaxReturn($response);
        }
    }

    public function edit()
    {
        if(IS_GET)
        {
            $store_id = I('get.store_id');
            $res = M('store')->find($store_id);

            if (empty($res)) {
                $this->redirect('index');
                exit;
            }
            $this->assign('data', $res);
            $this->display();
        }else{
            /**
             * 修改操作
             */
            $store = M('store');
            $data = I('post.');

            $store_id['store_id'] = array('eq',$data['store_id']);
            $store->store_name = trim($data['store_name']);
            $store->update_time = $_SERVER['REQUEST_TIME'];

            $result = $store->where($store_id)->save();
            if($result){
                    $response = array(
                        'status' => 'u_success',
                        'title' => Prompt::$StorePrompt['u_success'],
                    );
            }else{
                $response = array(
                    'status' => 'u_err',
                    'title' => Prompt::$StorePrompt['u_err'],
                );
            }

            return $this->ajaxReturn($response);
        }
    }

    public function del()
    {
        if (empty($_GET['store_id'])) {
            $this->redirect("index");
            exit;
        }
        $store_id = I('get.store_id');

        if (M('store')->delete($store_id)) {
            $response = array(
                'status' => 'd_success',
                'title' => Prompt::$StorePrompt['d_success'],
            );
        } else {
            $response = array(
                'status' => 'd_err',
                'title' => Prompt::$StorePrompt['d_err'],
            );
        }
        return $this->ajaxReturn($response);
    }
}